<?php

class Shippo_Transaction extends Shippo_ApiResource
{
    /**
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Shippo_Transaction Create a Transaction.
     */
    public static function create($params = null, $apiKey = null)
    {
        $class = get_class();
        return self::_scopedCreate($class, $params, $apiKey);
    }
    
    /**
     * @param array|null $params
     *
     * @return Shippo_Retrieve Get a Transaction.
     */
    public static function retrieve($id, $apiKey = null)
    {
        $class = get_class();
        return self::_scopedRetrieve($class, $id, $apiKey);
    }
    
    /**
     * @param array|null $params
     *
     * @return Shippo_All Get all the Transactions.
     */
    public static function all($params = null, $apiKey = null)
    {
        $class = get_class();
        return self::_scopedAll($class, $params, $apiKey);
    }
}
