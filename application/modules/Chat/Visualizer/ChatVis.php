<?php

class ChatVis
{


    public static function makeForm(&$chat)
    {
       return file_get_contents('forms/chatForm.php', FILE_USE_INCLUDE_PATH);
    }
}

