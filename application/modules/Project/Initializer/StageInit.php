<?php

class StageInit
{

    public $name;
    public $deskKeys = [];

    public function __construct($name, $desks)
    {
        $this->name = $name;
        $this->deskKeys = $desks;
    }

    public function __destruct()
    {

    }

}
