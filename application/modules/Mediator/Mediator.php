<?php

include_once APP . '/regex.php';
include_once APP . '/DataBase.php';
include_once APP . '/algorythm.php';
include_once APP . '/Dejavu.php';
include_once MOD . '/Router/Router.php';

class Mediator
{
    static function start()
    {
        $handler = Router::route();
        include_once MOD . '/' . $handler[0] . '/' . $handler[0] . '.php';
        DataBase::initialize();
       // self::DejavuRegisterClasses();
        call_user_func_array(array_shift($handler) . '::' . array_shift($handler), $handler);
        Dejavu::save();
    }

    static function DejavuRegisterClasses()
    {
        Dejavu::registerType('UserTmp', 'tmp_user', 'code', []);
        Dejavu::registerType('User', 'user', 'userid', ['projectsid' => true]);
        Dejavu::registerType('Project', 'project', 'projectid', ['usersid' => true, 'stagesid' => true]);
        Dejavu::registerType('Stage', 'stage', 'stageid', ['desksid' => true]);
        Dejavu::registerType('Desk', 'desk', 'deskid', ['cardsid' => true]);
        Dejavu::registerType('Card', 'card', 'cardid', ['name' => true, 'tasksid' => true, 'formid' => true,
            'status' => true, 'parentsid' => true, 'childrenid' => true, 'chatid']);
        Dejavu::registerType('Task', 'task', 'taskid', ['cardid' => true, 'deskid' => true, 'stageid' => true,
            'projectid' => true, 'executortype' => true, 'verifiertype' => true, 'executorid' => true, 'verifierid' => true,
            'text' => true, 'status' => true, 'startdate' => true, 'exhours' => true, 'verhours']);
    }

}
