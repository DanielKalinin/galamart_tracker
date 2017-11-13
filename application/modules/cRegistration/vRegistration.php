<?php

class vRegistration
{

    static function registrationForm($validRegInputs, $hasRegCookie)
    {
        $pageBody = file_get_contents('forms/registrationForm.php', FILE_USE_INCLUDE_PATH);

        include_once APP . '/pageTemplate.php';
    }

    static function confirmForm()
    {
        $pageBody = file_get_contents('forms/confirmForm.php', FILE_USE_INCLUDE_PATH);

        include_once APP . '/pageTemplate.php';
    }

}
