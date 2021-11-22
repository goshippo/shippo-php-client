<?php

class Shippo_Address extends Shippo_ApiResource
{
    /**
     * @param string $class Ignored.
     *
     * @return string The class URL for this resource. It needs to be special
     *    cased because it doesn't fit into the standard resource pattern.
     *    The standard resource pattern is name + s, e.g. parcel becomes parcels.
     */
    public static function classUrl($class)
    {
        return "/addresses";
    }
    
    /**
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Shippo_Address Create an Address.
     */
    public static function create($params = null, $apiKey = null)
    {
        $class = get_class();
        return self::_scopedCreate($class, $params, $apiKey);
    }
    
    /**
     * @param array|null $params
     *
     * @return Shippo_Retrieve Get an address.
     */
    public static function retrieve($id, $apiKey = null)
    {
        $class = get_class();
        return self::_scopedRetrieve($class, $id, $apiKey);
    }
    
    /**
     * @param array|null $params
     *
     * @return Shippo_All Get all the addresses.
     */
    public static function all($params = null, $apiKey = null)
    {
        $class = get_class();
        return self::_scopedAll($class, $params, $apiKey);
    }
    
    /**
     * @param array|null $params
     *
     * @return Shippo_Validate Validate an address.
     */
    public static function validate($id)
    {
        $class = get_class();
        return self::_scopedValidate($class, $id);
    }
}
