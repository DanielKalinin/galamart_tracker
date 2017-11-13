<?php

include_once 'mDownload.php';
include_once 'vDownload.php';

class cDownload
{
    static function download($fileid)
    {
        if (mDownload::isAuth())
        {
            vDownload::send(mDownload::findFile($fileid));
        }
        else
        {
            http_response_code(404);
        }
    }
}