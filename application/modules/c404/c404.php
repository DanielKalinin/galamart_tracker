<?php

class c404
{
    static function index()
    {
        http_response_code(404);
    }
}
