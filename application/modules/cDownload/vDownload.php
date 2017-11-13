<?php

class vDownload
{

    static function send($file)
    {
        $tmp_name = $fileid . '_tmp_for_dl';
        file_put_contents($tmp_name, $file['data']);
        if (ob_get_level())
        {
            ob_end_clean();
        }
        header('Content-deskription: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file['name']));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($tmp_name));
        readfile($tmp_name);
        unlink($tmp_name);
    }

}
