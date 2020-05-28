<?php

namespace App\controller;

use App\core\Controller;

class AdminController extends Controller
{
    public function loginAction()
    {
        $params = [];

        if (isset($_POST['submit'])) {
            $params = $this->model->login();
            if(!count($params['error']) && isset($_SESSION['id'])) {
                $this->redirect('/');
            }
        }

        $this->view->renderer('/admin/login.phtml', $params);
    }

    public function logoutAction()
    {
        $_SESSION = [];
        session_destroy();
        $this->redirect('/');
    }
}
