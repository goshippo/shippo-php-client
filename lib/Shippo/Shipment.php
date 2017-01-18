<?php

class Shippo_Shipment extends Shippo_ApiResource
{
    /**
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Shippo_Shipment Create a Shipment.
     */
    public static function create($params = null, $apiKey = null)
    {
        $class = get_class();
        return self::_scopedCreate($class, $params, $apiKey);
    }
    
    /**
     * @param string $id
     * @param string|null $apiKey
     *
     * @return Shippo_Retrieve Get a Shipment.
     */
    public static function retrieve($id, $apiKey = null)
    {
        $class = get_class();
        return self::_scopedRetrieve($class, $id, $apiKey);
    }
    
    /**
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Shippo_All Get all the Shipments.
     */
    public static function all($params = null, $apiKey = null)
    {
        $class = get_class();
        return self::_scopedAll($class, $params, $apiKey);
    }
    
    /**
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Shippo_Get_Shipping_Rates Get the rates for a Shipment.
     */
    public static function get_shipping_rates($params = null, $apiKey = null)
    {
        $class = get_class();
        $id = $params['id'];
        return self::_scopedGet($class, $id, $params, $apiKey = null);
    }
}
