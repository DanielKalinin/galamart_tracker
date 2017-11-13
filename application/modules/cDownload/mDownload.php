<?php

class mDownload
{
    static function isAuth()
    {
        session_start();
        return !empty($_SESSION['user']);
    }

    static function findFile($fileid)
    {
        $db = DataBase::get();
        $querry = $db->prepare('SELECT name, data FROM file WHERE fileid=?');
        $querry->execute([$fileid]);
        $file = $querry->fetch();
        return $file;
    }
}
