<?php

class DeskInit
{

    public $name;
    public $cardKeys = [];

    public function __construct($name, $cards)
    {
        $this->name = $name;
        $this->cardKeys = $cards;
    }

    public function __destruct()
    {

    }

}
