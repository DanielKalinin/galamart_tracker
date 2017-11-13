<?php

class Area
{
    var $areaid;
    var $name;

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


    function loadId($areaid)
    {
        $db = DataBase::get();

        $querry = $db->prepare('SELECT * FROM area WHERE areaid=? LIMIT 1');
        $querry->execute([$areaid]);

        $area = $querry->fetch();
        $area['areaid'] = intval($area['areaid']);
        $this->assign($area);
    }


    function loadName($name)
    {
        $db = DataBase::get();

        $querry = $db->prepare('SELECT * FROM area WHERE name=? LIMIT 1');
        $querry->execute([$name]);

        $area = $querry->fetch();
        $area['areaid'] = intval($area['name']);
        $this->assign($area);
    }


    function assign($area)
    {
        $this->areaid = $area['areaid'];
        $this->name = $area['name'];
    }

}
