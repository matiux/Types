<?php namespace Matiux\Types;

class Object
{
    public static function obj_to_array($obj)
    {
        if (is_object($obj))
            $obj = get_object_vars($obj);

        if (is_array($obj))
            return array_map(__METHOD__, $obj);
        else
            return $obj;
    }

    public static function array_to_obj($array)
    {
        if (is_array($array))
            return (object) array_map(__METHOD__, $array);
        else
            return $array;
    }

    public static function name($obj)
    {
        if (!is_object($obj))
            throw new \InvalidArgumentException('Object::name() method accepts only objects');

        $fully      = get_class($obj);
        $chunks     = explode('\\', $fully);

        return $chunks[count($chunks) - 1];
    }

    public static function nameByStr($objString, $sep = '\\')
    {
        if (is_object($objString))
            throw new \InvalidArgumentException('Object::nameByStr() method accepts only strings');

        $chunks = explode($sep, $objString);

        return $chunks[count($chunks) - 1];

    }
}
