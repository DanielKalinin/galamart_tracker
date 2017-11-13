<?php

class vMain
{

    static function makeMain()
    {
        session_start();
        $pageBody = file_get_contents('pages/mainPage.php', FILE_USE_INCLUDE_PATH);

        include_once APP . '/pageTemplate.php';
    }

}
