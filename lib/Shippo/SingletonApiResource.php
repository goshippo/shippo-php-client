<?php

abstract class Shippo_SingletonApiResource extends Shippo_ApiResource
{
    protected static function _scopedSingletonRetrieve($class, $apiKey = null)
    {
        $instance = new $class(null, $apiKey);
        $instance->refresh();
        return $instance;
    }
    
    /**
     * @param Shippo_SingletonApiResource $class
     * @return string The endpoint associated with this singleton class.
     */
    public static function classUrl($class)
    {
        $base = self::className($class);
        return "/${base}";
    }
    
    /**
     * @return string The endpoint associated with this singleton API resource.
     */
    public function instanceUrl()
    {
        $class = get_class($this);
        $base = self::classUrl($class);
        return "$base";
    }
}
