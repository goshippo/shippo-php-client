<?php

namespace Shippo;

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
        return "/v1/addresses";
    }

    /**
     * @param array|null $params
     * @param string|null $apiKey
     * @return static
     */
    public static function create($params = null, $apiKey = null)
    {
        $class = get_class();
        return self::_scopedCreate($class, $params, $apiKey);
    }

    /**
     * @param $id
     * @param null $apiKey
     * @return static
     */
    public static function retrieve($id, $apiKey = null)
    {
        $class = get_class();

        return self::_scopedRetrieve($class, $id, $apiKey);
    }

    /**
     * @param null $params
     * @param null $apiKey
     * @return static
     */
    public static function all($params = null, $apiKey = null)
    {
        $class = get_class();
        return self::_scopedAll($class, $params, $apiKey);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function validate($id)
    {
        $class = get_class();
        return self::_scopedValidate($class, $id);
    }
}
