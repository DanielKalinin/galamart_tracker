<?php

class Stage
{

    static function makeForm(&$stage)
    {
        return file_get_contents('forms/stageForm.php', FILE_USE_INCLUDE_PATH);
    }

}