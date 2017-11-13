<?php

class DataBase
{

    static $db;

    static function initialize()
    {
//        if ($_SERVER['SERVER_NAME'] == 'franchgala.tk')
            self::$db = new PDO('mysql:dbname=cp66918_franch;host=localhost', 'cp66918_franch', 'sescurfu282');
//        else if ($_SERVER['SERVER_NAME'] == 'franchgalatest.tk')
//            self::$db = new PDO('mysql:dbname=cp66918_test;host=localhost', 'cp66918_test', 'sescurfu282');
 //       self::$db = new PDO('mysql:dbname=franchising;host=127.0.0.1', 'root', '');
    }

    static function &get() : PDO
    {
        return self::$db;
    }

}
