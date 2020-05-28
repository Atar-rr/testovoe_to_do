<?php

namespace App\core;

class View
{
    public function renderer($view, $params = [])
    {
        $path = __DIR__ . "/../view/$view";
        require_once $path;
        die();
    }

    public function error($view, $code)
    {
        http_response_code($code);
        $path = __DIR__ . "/../view/$view";
        require_once $path;
        die();
    }
}
