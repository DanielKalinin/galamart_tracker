<?php

class MessageVis
{


    public static function makeForm(&$message)
    {
       return file_get_contents('forms/messageForm.php', FILE_USE_INCLUDE_PATH);
    }
}
