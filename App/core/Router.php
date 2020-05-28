<?php

namespace App\core;

class Router
{
    private $routes;
    private $params;
    private $query;
    private $uri;
    private $path;
    private $view;

    public function __construct()
    {
        $this->routes = require_once __DIR__ . '/../config/router.php';
        $this->view = new View();
    }

    private function parseUri()
    {
        $this->uri = trim($_SERVER['REQUEST_URI'], '/');
        $this->query = parse_url($this->uri, PHP_URL_QUERY);
        $this->path = parse_url($this->uri, PHP_URL_PATH);
    }


    private function getController()
    {
        return $this->params['controller'] . 'Controller';
    }

    private function getAction()
    {
        return $this->params['action'] . 'Action';
    }

    private function match()
    {
        $this->parseUri();
        foreach ($this->routes as $route => $params) {
            if (preg_match("~^$route$~", $this->path)) {
                $this->params = $params;
                return true;
            }
        }

        return false;
    }

    public function run()
    {
        if ($this->match()) {
            $path = 'App\controller\\' . ucfirst($this->getController());
            if (class_exists($path)) {

                $controller = new $path($this->params, $this->query);
                $action = $this->getAction();
                if (method_exists($controller, $action)) {
                    $controller->$action();
                } else {
                    $this->view->error('/error/404.phtml', 404);

                }
            } else {
                $this->view->error('/error/404.phtml', 404);
            }
        } else {
            $this->view->error('/error/404.phtml', 404);
        }
    }
}
