<?php

class vAuthorization
{

    static function authorizationForm($isValidAuth)
    {
        $pageBody = file_get_contents('forms/authorizationForm.php', FILE_USE_INCLUDE_PATH);

        include_once APP . '/pageTemplate.php';
    }

    static function repairForm($validPnum)
    {
        $pageBody = file_get_contents('forms/repairForm.php', FILE_USE_INCLUDE_PATH);

        include_once APP . '/pageTemplate.php';
    }

    static function successRepair()
    {
        $pageBody = file_get_contents('forms/successRepair.php', FILE_USE_INCLUDE_PATH);

        include_once APP . '/pageTemplate.php';
    }

    static function confirmForm($validConfirm)
    {
        $pageBody = file_get_contents('forms/confirmForm.php', FILE_USE_INCLUDE_PATH);

        include_once APP . '/pageTemplate.php';
    }

}
