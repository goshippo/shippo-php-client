<?php

class Shippo_Rate extends Shippo_ApiResource
{
    /**
     * @param array|null $params
     *
     * @return Shippo_Retrieve Get a Rate.
     */
    public static function retrieve($id, $apiKey = null)
    {
        $class = get_class();
        return self::_scopedRetrieve($class, $id, $apiKey);
    }
    
    /**
     * @param array|null $params
     *
     * @return Shippo_All Get all the Rates.
     */
    public static function all($params = null, $apiKey = null)
    {
        $class = get_class();
        return self::_scopedAll($class, $params, $apiKey);
    }
}
