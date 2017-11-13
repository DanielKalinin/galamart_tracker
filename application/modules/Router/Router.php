<?php

class Router
{

    public static function route()
    {
        $routes = include_once 'routes.php';
        $array = [];
        foreach ($routes as $request => $path)
        {
            $path[2] = preg_replace("~$request~", $path[2], $_SERVER['REQUEST_URI'], -1, $reps);

            if ($reps)
            {
                $array[] = $path[0];
                $array[] = $path[1];
                $array = array_merge($array, explode(' ', $path[2]));
                break;
            }
        }
        return $array;
    }

}
