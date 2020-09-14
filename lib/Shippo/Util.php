<?php

abstract class Shippo_Util
{
    /**
     * Whether the provided array (or other) is a list rather than a dictionary.
     *
     * @param array|mixed $array
     * @return boolean True if the given object is a list.
     */
    public static function isList($array)
    {
        if (!is_array($array))
            return false;

        // TODO: generally incorrect, but it's correct given Shippo's response
        foreach (array_keys($array) as $k) {
            if (!is_numeric($k))
                return false;
        }
        return true;
    }

    /**
     * Recursively converts the PHP Shippo object to an array.
     *
     * @param array $values The PHP Shippo object to convert.
     * @return array
     */
    public static function convertShippoObjectToArray($values)
    {
        $results = array();
        foreach ($values as $k => $v) {
            // FIXME: this is an encapsulation violation
            if (is_string($k) && $k[0] == '_') {
                continue;
            }
            if ($v instanceof Shippo_Object) {
                $results[$k] = $v->__toArray(true);
            } else if (is_array($v)) {
                $results[$k] = self::convertShippoObjectToArray($v);
            } else {
                $results[$k] = $v;
            }
        }
        return $results;
    }

    /**
     * Converts a response from the Shippo API to the corresponding PHP object.
     *
     * @param array $resp The response from the Shippo API.
     * @param string $apiKey
     * @return Shippo_Object|array
     */
    public static function convertToShippoObject($resp, $apiKey)
    {
        // TODO: Have API Return Object: Type in order to cast properly
        $types = array(
            'QUOTE' => 'Shippo_Address'
        );
        if (self::isList($resp)) {
            $mapped = array();
            foreach ($resp as $i)
                array_push($mapped, self::convertToShippoObject($i, $apiKey));
            return $mapped;
        } else if (is_array($resp)) {
            if (isset($resp['object_purpose']) && is_string($resp['object_purpose']) && isset($types[$resp['object_purpose']])) {
                $class = $types[$resp['object_purpose']];
            } else {
                $class = 'Shippo_Object';
            }
            return Shippo_Object::scopedConstructFrom($class, $resp, $apiKey);
        } else {
            return $resp;
        }
    }

    /**
     * @param string|mixed $value A string to UTF8-encode.
     *
     * @returns string|mixed The UTF8-encoded string, or the object passed in if
     *    it wasn't a string.
     */
    public static function utf8($value)
    {
        if (is_string($value) && mb_detect_encoding($value, "UTF-8", TRUE) != "UTF-8") {
            return utf8_encode($value);
        } else {
            return $value;
        }
    }
}
