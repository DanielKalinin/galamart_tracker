<?php

include_once 'CardForm.php';

class CardInit
{

    var $name;
    var $form;
    var $taskKeys = [];
    var $parentKeys = [];
    var $childrenKeys = [];

    function __construct($name, $form, $tasks, $parents, $children)
    {
        $this->name = $name;
        $this->form = $form;
        $this->taskKeys = $tasks;
        $this->parentKeys = $parents;
        $this->childrenKeys = $children;
    }

    function __destruct()
    {

    }

}
