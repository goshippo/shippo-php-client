<?php

class Shippo_Pickup extends Shippo_ApiResource
{
    /**
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Shippo_Pickup Create a pickup.
     */
    public static function create($params = null, $apiKey = null)
    {
        $class = get_class();
        return self::_scopedCreate($class, $params, $apiKey);
    }
}
