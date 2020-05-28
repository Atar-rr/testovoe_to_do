<?php

use App\core\Router;

include __DIR__ . '/vendor/autoload.php';

$router = new Router();

session_start();

$router->run();
