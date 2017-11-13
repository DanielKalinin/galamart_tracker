<?php

class TaskVis
{

    static function makeForm(&$task, &$user)
    {
        return file_get_contents('forms/taskForm.php', FILE_USE_INCLUDE_PATH);
    }

}