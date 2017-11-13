<?php

class TaskInit
{

    public $executortype;
    public $verifiertype;
    public $text;
    public $exhours;
    public $verhours;

    public function __construct($executortype, $verifiertype, $text, $exdays, $verdays = 1)
    {
        $this->executortype = $executortype;
        $this->verifiertype = $verifiertype;
        $this->text = $text;
        $this->exhours = $exdays * 24;
        $this->verhours = $verdays * 24;
    }

    public function __destruct()
    {

    }

}
