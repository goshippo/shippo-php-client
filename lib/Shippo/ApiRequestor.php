<?php
class Shippo_ApiRequestor
{
    /**
     * @var string $apiKey The API key that's to be used to make requests.
     */
    public $apiKey;
    
    public function __construct($apiKey = null)
    {
        $this->_apiKey = $apiKey;
    }
    
    /**
     * @param string $url The path to the API endpoint.
     *
     * @returns string The full path.
     */
    public static function apiUrl($url = '')
    {
        $apiBase = Shippo::$apiBase;
        return "$apiBase$url";
    }
    
    /**
     * @param string|mixed $value A string to UTF8-encode.
     *
     * @returns string|mixed The UTF8-encoded string, or the object passed in if
     *    it wasn't a string.
     */
    public static function utf8($value)
    {
        if (is_string($value) && mb_detect_encoding($value, "UTF-8", TRUE) != "UTF-8") {
            return utf8_encode($value);
        } else {
            return $value;
        }
    }
    
    private static function _encodeObjects($d)
    {
        if ($d instanceof Shippo_ApiResource) {
            return self::utf8($d->id);
        } else if ($d instanceof Shippo_Object) {
            return self::utf8($d->object_id);
        } else if ($d === true) {
            return true;
        } else if ($d === false) {
            return false;
        } else if (is_array($d)) {
            $res = array();
            foreach ($d as $k => $v)
                $res[$k] = self::_encodeObjects($v);
            return $res;
        } else {
            return self::utf8($d);
        }
    }
    
    /**
     * @param array $arr An map of param keys to values.
     * @param string|null $prefix (It doesn't look like we ever use $prefix...)
     *
     * @returns string A querystring, essentially.
     */
    public static function encode($arr, $prefix = null)
    {
        if (!is_array($arr))
            return $arr;
        
        $r = array();
        foreach ($arr as $k => $v) {
            if (is_null($v))
                continue;
            
            if ($prefix && $k && !is_int($k))
                $k = $prefix . "[" . $k . "]";
            else if ($prefix)
                $k = $prefix . "[]";
            
            if (is_array($v)) {
                $r[] = self::encode($v, $k, true);
            } else {
                $r[] = urlencode($k) . "=" . urlencode($v);
            }
        }
        
        return implode("&", $r);
    }
    
    /**
     * @param string $method
     * @param string $url
     * @param array|null $params
     *
     * @return array An array whose first element is the response and second
     *    element is the API key used to make the request.
     */
    public function request($method, $url, $params = null)
    {
        if (!$params)
            $params = array();
        list($rbody, $rcode, $myApiKey) = $this->_requestRaw($method, $url, $params);
        $resp = $this->_interpretResponse($rbody, $rcode);
        return array(
            $resp,
            $myApiKey
        );
    }
    
    
    /**
     * @param string $rbody A JSON string.
     * @param int $rcode
     * @param array $resp
     *
     * @throws Shippo_InvalidRequestError if the error is caused by the user.
     * @throws Shippo_AuthenticationError if the error is caused by a lack of
     *    permissions.
     * @throws Shippo_ApiError otherwise.
     */
    public function handleApiError($rbody, $rcode, $resp)
    {
        // Array is not currently being returned by API, making the below N/A 
        // if (!is_array($resp) || !isset($resp['error'])) {
        //   $msg = "Invalid response object from API: $rbody "
        //        ."(HTTP response code was $rcode)";
        //   throw new Shippo_ApiError($msg, $rcode, $rbody, $resp);
        // }
        
        $msg = "message not set";
        $param = "parameters not set";
        $code = "code not set";
        
        // Temporary setting of msg to rbody
        $msg = $rbody;
        
        // Parameters necessary for error code construction are not provided
        // $error = $resp['error'];
        // $msg = isset($error['message']) ? $error['message'] : null;
        // $param = isset($error['param']) ? $error['param'] : null;
        // $code = isset($error['code']) ? $error['code'] : null;
        
        switch ($rcode) {
            case 400:
                throw new Shippo_InvalidRequestError($msg, $param, $rcode, $rbody, $resp);
            case 404:
                throw new Shippo_InvalidRequestError($msg, $param, $rcode, $rbody, $resp);
            case 401:
                throw new Shippo_AuthenticationError($msg, $rcode, $rbody, $resp);
            default:
                throw new Shippo_ApiError($msg, $rcode, $rbody, $resp);
        }
    }
    
    private function _requestRaw($method, $url, $params)
    {
        $myApiKey = $this->_apiKey;
        if (!$myApiKey)
            $myApiKey = Shippo::$apiKey;
        
        if (!$myApiKey) {
            $msg = 'No credentials provided.';
            throw new Shippo_AuthenticationError($msg);
        }
        
        $absUrl = $this->apiUrl($url);
        $params = self::_encodeObjects($params);
        $langVersion = phpversion();
        $uname = php_uname();
        $headers = array(
            'Content-Type: application/json',
            'Authorization: ShippoToken ' . $myApiKey,
            'Accept: application/json',
            'User-Agent: Shippo/v1 PHPBindings/' . Shippo::VERSION
        );
        
        list($rbody, $rcode) = $this->_curlRequest($method, $absUrl, $headers, $params);
        return array(
            $rbody,
            $rcode,
            $myApiKey
        );
    }
    
    private function _interpretResponse($rbody, $rcode)
    {
        try {
            $resp = json_decode($rbody, true);
        }
        catch (Exception $e) {
            $msg = "Invalid response body from API: $rbody " . "(HTTP response code was $rcode)";
            throw new Shippo_ApiError($msg, $rcode, $rbody);
        }
        
        if ($rcode < 200 || $rcode >= 300) {
            $this->handleApiError($rbody, $rcode, $resp);
        }
        return $resp;
    }
    
    private function _curlRequest($method, $absUrl, $headers, $params)
    {
        $curl = curl_init();
        $method = strtolower($method);
        $curlOptions = array();
        
        // Request Method
        if ($method == 'get') {
            $curlOptions[CURLOPT_HTTPGET] = 1;
            if (count($params) > 0) {
                $encoded = self::encode($params);
                $absUrl = "$absUrl?$encoded";
            }
        } else if ($method == 'post') {
            $curlOptions[CURLOPT_POST] = 1;
            $curlOptions[CURLOPT_POSTFIELDS] = json_encode($params);  
        } else if ($method == 'put') {
            $curlOptions[CURLOPT_CUSTOMREQUEST] = 'PUT';
            $curlOptions[CURLOPT_POSTFIELDS] = json_encode($params);
        } else {
            throw new Error("Unrecognized method {$method}");
        }
        // echo("<br><br>ABSOLUTE URL: " . $absUrl . "<br><br>");
        $absUrl = self::utf8($absUrl);
        $curlOptions[CURLOPT_URL] = $absUrl;
        $curlOptions[CURLOPT_RETURNTRANSFER] = true;
        $curlOptions[CURLOPT_CONNECTTIMEOUT] = 30;
        $curlOptions[CURLOPT_TIMEOUT] = 80;
        $curlOptions[CURLOPT_HTTPHEADER] = $headers;
        
        curl_setopt_array($curl, $curlOptions);
        $httpBody = curl_exec($curl);
        
        $errorNum = curl_errno($curl);
        if ($errorNum == CURLE_SSL_CACERT || $errorNum == CURLE_SSL_PEER_CERTIFICATE || $errorNum == 77) {
            curl_setopt($curl, CURLOPT_CAINFO, dirname(__FILE__) . '/../cacert.pem');
            $httpBody = curl_exec($curl);
        }
        
        if ($httpBody === false) {
            $errorNum = curl_errno($curl);
            $message = curl_error($curl);
            curl_close($curl);
            $this->handleCurlError($errorNum, $message);
        }
        
        $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        // echo("<br><br>RESPONSE<br><br>".$httpBody);
        // echo("<br><br>STATUS<br><br>".$httpStatus);
        return array(
            $httpBody,
            $httpStatus
        );
    }
    
    /**
     * @param number $errno
     * @param string $message
     * @throws Shippo_ApiConnectionError
     */
    public function handleCurlError($errno, $message)
    {
        $apiBase = Shippo::$apiBase;
        switch ($errno) {
            case CURLE_COULDNT_CONNECT:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_OPERATION_TIMEOUTED:
                $msg = "Could not connect to Shippo ($apiBase).";
                break;
            default:
                $msg = "Unexpected error communicating with Shippo.  " . "If this problem persists,";
        }
        $msg .= " let us know by contacting us through our contact form.";
        $msg .= "\n\n(Network error [errno $errno]: $message)";
        throw new Shippo_ApiConnectionError($msg);
    }
}
