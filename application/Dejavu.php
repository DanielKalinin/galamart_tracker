<?php

include_once "DataBase.php";

class Dejavu
{
    static $register = [];
    static $registry = [];

    ////////////////////////////////////////////////////////////////////////////
    //////////////////////////       register       ////////////////////////////
    static function registerType($type, $tableName, $idName, $properties)
    {
        self::$register[$type]['tableName'] = $tableName;
        self::$register[$type]['idName'] = $idName;
        self::$register[$type]['properties'] = $properties;
    }

    private static function isTypeRegistered($type)
    {
        return !empty(self::$register[$type]);
    }

    private static function getTableName($type)
    {
        return self::$register[$type]['tableName'];
    }

    private static function getIdName($type)
    {
        return self::$register[$type]['idName'];
    }

    ////////////////////////////////////////////////////////////////////////////
    //////////////////////////       registry       ////////////////////////////
    private static function loadObject($type, $initData)
    {
        /*$tableName = self::getTableName($type);
        $idCollumnName = self::getIdName($type);
        $db = DataBase::get();
        $querry = $db->prepare("SELECT $idCollumnName FROM $tableName WHERE $idCollumnName=? LIMIT 1");
        $array = $querry->execute([$id]);
        if (empty($array))
            return null;
        foreach ($array as $propertyName => &$property)
        {
            if (self::$registry['properties'][$propertyName])
                $property = explode_to_int(' ', $property);
        }*/
        $object = new $type($initData);
        $idName = self::getIdName($type);
        self::$registry[$type][$object->$idName] = $object;
    }

    static function getObject($type, $initData)
    {
        $object = new $type($initData);
        $idname = strtolower($type) . 'id';
        $id = $object->$idname;

        if (empty(self::$registry[$type][$id]))
        {
            self::$registry[$type][$id] = $object;
        }

        return self::$registry[$type][$id];
    }

    static function removeObject($type, $id)
    {
        if (array_key_exists($type, self::$registry))
            unset(self::$registry[$type][$id]);
        else
            return false;
    }

    ////////////////////////////////////////////////////////////////////////////
    //////////////////////////        saving         ///////////////////////////
    static function save()
    {
        foreach (self::$registry as $type => $objects)
        {
            foreach ($objects as $id => $object)
            {
                if(method_exists($type, "save"))
                    $object->save();
            }
        }
    }
}