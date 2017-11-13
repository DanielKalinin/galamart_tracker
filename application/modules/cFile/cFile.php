<?php

class cFile
{
    static function image($image)
    {
        self::upload(ROOT . '/images/' . $image, 'image/png');
    }

    static function style($style)
    {
        self::upload(ROOT . '/styles/' . $style, 'text/css');
    }

    static function upload($file, $content)
    {
        if (file_exists($file))
        {
            if (ob_get_level())
              ob_end_clean();

            header('Content-Type: ' . $content);
            readfile($file);
        }
        else
            http_response_code(404);
    }

    static function uploadForm($file)
    {
        if (file_exists($file))
        {
            if (ob_get_level())
              ob_end_clean();

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));

            readfile($file);
        }
        else
            echo http_response_code(404);
    }
}
