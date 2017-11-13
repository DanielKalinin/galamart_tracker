<?php

include_once 'TaskVis.php';
include_once '../CardLogger.php';

class CardVis
{

    static function makeForm(&$card, &$user)
    {
        return file_get_contents('forms/cardForm.php', FILE_USE_INCLUDE_PATH);
    }

}
