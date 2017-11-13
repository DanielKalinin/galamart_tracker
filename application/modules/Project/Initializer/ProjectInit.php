<?php

class ProjectInit
{

    public $stageKeys = [];

    public function __construct($stages)
    {
        $this->stageKeys = $stages;
    }

    public function __destruct()
    {

    }

}
