<?php

class Shippo_Batch extends Shippo_ApiResource
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
        return "/batches";
    }

    /**
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Shippo_Batch Create a batch shipment object 
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
     * @return Shippo_Retrieve Retrieves a batch shipment 
     */
    public static function retrieve($id, $apiKey = null)
    {
        $class = get_class();
        return self::_scopedRetrieve($class, $id, $apiKey);
    }

     /**
     * @param string $id
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Shippo_Add Adds shipments to a batch
     */
    public static function add($id, $params = null, $apiKey = null)
    {
        $class = get_class();
        return self::_scopedAddBatch($class, $id, $params, $apiKey);
    }

     /**
     * @param string $id
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Shippo_Remove Removes shipments from a batch
     */
    public static function remove($id, $params = null, $apiKey = null)
    {
        $class = get_class();
        return self::_scopedRemoveBatch($class, $id, $params, $apiKey);
    }

     /**
     * @param string $id
     * @param string|null $apiKey
     *
     * @return Shippo_Purchase Attempts to purchase a batch shipment 
     */
    public static function purchase($id, $apiKey = null)
    {
        $class = get_class();
        return self::_scopedPurchaseBatch($class, $id, $apiKey);
    }
}
