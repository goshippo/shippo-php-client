<?php

abstract class Shippo
{
    /**
     * @var string The Shippo API key to be used for requests. 
     */
    public static $apiKey;
    /**
     * @var string The base URL for the Shippo API.
     */
    public static $apiBase = 'https://api.goshippo.com';
    /**
     * @var string|null The version of the Shippo API to use for requests.
     */
    public static $apiVersion = null;
    /**
     * @var boolean Defaults to true.
     */
    public static $verifySslCerts = false;
    const VERSION = '0.0.1';
    
    /**
     * @return string The API key used for requests.
     */
    public static function getApiKey()
    {
        return self::$apiKey;
    }
    
    /**
     * Sets the API key to be used for requests.
     *
     * @param string $apiKey
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }
    
    /**
     * @return string The API version used for requests. null if we're using the
     *    latest version.
     */
    public static function getApiVersion()
    {
        return self::$apiVersion;
    }
    
    /**
     * @param string $apiVersion The API version to use for requests.
     */
    public static function setApiVersion($apiVersion)
    {
        self::$apiVersion = $apiVersion;
    }
}
