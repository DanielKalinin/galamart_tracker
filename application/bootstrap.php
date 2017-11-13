<?php

define('APP', ROOT . '/application');
define('MOD', APP . '/modules');
define('URI', $GLOBALS['URI'] = rtrim($_SERVER['REQUEST_URI'], '/'));

include_once MOD . '/Mediator/Mediator.php';

Mediator::start();

//include_once APP . '/scripts/50_notification_script.php';