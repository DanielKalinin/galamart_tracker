<?php

class City
{

    var $name;
    var $cityid;
    var $areaid;


    function __construct($initData)
    {
        if (is_int($initData))
        {
            $this->loadId($initData);
        }
        else if (is_string($initData))
        {
            $this->loadName($initData);
        }
        else
        {
            $this->assign($initData);
        }
    }


    function loadId($cityid)
    {
        $db = DataBase::get();

        $querry = $db->prepare('SELECT * FROM city WHERE cityid=? LIMIT 1');
        $querry->execute([$cityid]);

        $city = $querry->fetch();
        $city['cityid'] = intval($city['cityid']);
        $city['areaid'] = intval($city['areaid']);
        $this->assign($city);
    }


    function loadName($name)
    {
        $db = DataBase::get();

        $querry = $db->prepare('SELECT * FROM city WHERE name=? LIMIT 1');
        $querry->execute([$name]);

        $city = $querry->fetch();
        $city['cityid'] = intval($city['cityid']);
        $city['areaid'] = intval($city['areaid']);
        $this->assign($city);
    }


    function assign($city)
    {
        $this->cityid = $city['cityid'];
        $this->name = $city['name'];
        $this->areaid = $city['areaid'];
    }

}
