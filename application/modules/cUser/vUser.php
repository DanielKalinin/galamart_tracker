<?php

class vUser
{

    static function makeProfile($profileInfo)
    {
        $pageBody = file_get_contents('forms/profileInfo.php', FILE_USE_include_once_PATH);
        include_once APP . '/pageTemplate.php';
    }

}
