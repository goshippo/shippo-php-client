<?php
class Shippo_ApiRequestor
{
    /**
     * @var string $apiKey The API key that's to be used to make requests.
     */
    public $apiKey;
    private static $httpClient;
    
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
    
    private static function _encodeObjects($d)
    {
        if ($d instanceof Shippo_ApiResource) {
            return Shippo_Util::utf8($d->id);
        } else if ($d instanceof Shippo_Object) {
            return Shippo_Util::utf8($d->object_id);
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
            return Shippo_Util::utf8($d);
        }
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

    public function getRequestHeaders()
    {
        $apiKey = $this->_getApiKey();

        $headers = array(
            'Content-Type: application/json',
            'Authorization: ' . $this->_getAuthorizationType($apiKey) . ' ' . $apiKey,
            'Accept: application/json',
            'User-Agent: Shippo/v1 PHPBindings/' . Shippo::VERSION
        );
        if (Shippo::getApiVersion()){
            $headers[] = 'Shippo-API-Version: ' . Shippo::getApiVersion();
        }

        return $headers;
    }
    
    private function _requestRaw($method, $url, $params)
    {
        $absUrl = $this->apiUrl($url);
        $params = self::_encodeObjects($params);
        $myApiKey = $this->_getApiKey();
        $headers = $this->getRequestHeaders();
        
        list($rbody, $rcode) = $this->httpClient()->request($method, $absUrl, $headers, $params);
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

    private function _getApiKey()
    {
        $apiKey = $this->_apiKey;
        if (!$apiKey)
            $apiKey = Shippo::$apiKey;

        if (!$apiKey) {
            throw new Shippo_AuthenticationError('No credentials provided.');
        }

        return $apiKey;
    }

    private function _getAuthorizationType($apiKey = '')
    {
        return strpos($apiKey, 'oauth.') === 0 ? 'Bearer' : 'ShippoToken';
    }

    public static function setHttpClient($client)
    {
        self::$httpClient = $client;
    }

    public static function httpClient()
    {
        if (!self::$httpClient) {
            self::$httpClient = CurlClient::instance();
        }
        return self::$httpClient;
    }
}
