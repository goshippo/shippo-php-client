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
}
