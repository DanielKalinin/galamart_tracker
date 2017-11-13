<?php

class Desk
{

    static function makeForm(&$desk)
    {
        return file_get_contents('forms/deskForm.php', FILE_USE_INCLUDE_PATH);
    }

}
