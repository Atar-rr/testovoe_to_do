<?php

namespace App\core;

use App\core\View;

abstract class Controller
{
    protected $routes = [];
    protected $view;
    protected $model;
    protected $query;

    public function __construct($routes = [], $query = '')
    {
        $this->routes = $routes;
        $this->view = new View();
        $this->query = $query;
        $model = $this->createModel();
        if(class_exists($model)) {
            $this->model = new $model();
        }
    }

    public function redirect($url)
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $url, true, 303);
        die();
    }

    public function auth()
    {
        if (!isset($_SESSION['id'])) {
            $this->view->error('/error/403.phtml', 403);
        }
    }

    private function createModel()
    {
        return 'App\model\\' . ucfirst($this->getController());
    }

    public function getController()
    {
        return $this->routes['controller'];
    }

    public function getAction()
    {
        return $this->routes['action'];
    }
}
