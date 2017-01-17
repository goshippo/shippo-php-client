<?php

class Shippo_List extends Shippo_Object
{
    public function all($params = null)
    {
        $requestor = new Shippo_ApiRequestor($this->_apiKey);
        list($response, $apiKey) = $requestor->request('get', $this['url'], $params);
        return Shippo_Util::convertToShippoObject($response, $apiKey);
    }
    
    public function create($params = null)
    {
        $requestor = new Shippo_ApiRequestor($this->_apiKey);
        list($response, $apiKey) = $requestor->request('post', $this['url'], $params);
        return Shippo_Util::convertToShippoObject($response, $apiKey);
    }
    
    public function retrieve($id, $params = null)
    {
        $requestor = new Shippo_ApiRequestor($this->_apiKey);
        $base = $this['url'];
        $id = Shippo_Util::utf8($id);
        $extn = urlencode($id);
        list($response, $apiKey) = $requestor->request('get', "$base/$extn", $params);
        return Shippo_Util::convertToShippoObject($response, $apiKey);
    }
    
}
