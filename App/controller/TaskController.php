<?php

namespace App\controller;

use App\core\Controller;

class TaskController extends Controller
{
    public function showAction()
    {
        parse_str($this->query, $output);
        $params = $this->model->show($output['page'] ?? 1, $output['sort'] ?? 0);
        $this->view->renderer('/task/show.phtml', $params);
    }

    public function createAction()
    {
        $params = [];
        if (isset($_POST['submit'])) {
            $params = $this->model->create();

            if (!count($params['error'])) {
                $_SESSION['task'] = 'Задача создана';
                $this->redirect('/');
            }
        }
        $this->view->renderer('/task/create.phtml', $params);
    }

    public function editAction()
    {
        $this->auth();
        parse_str($this->query, $output);
        $params = $this->model->edit($output['id'] ?? '');
        if ($params) {
            $this->view->renderer('/task/edit.phtml', $params);
        } else {
            $this->view->error('/error/404.phtml', 404);
        }
    }

    public function updateAction()
    {
        $this->auth();
        parse_str($this->query, $output);
        if (isset($_POST['update']) && isset($_POST['_METHOD'])) {
            if ($_POST['_METHOD'] === 'PATCH') {
                $this->model->update();
                $_SESSION['task'] = 'Задача отредактирована';
                $this->redirect('/');
            }
        }
    }

    public function completeAction()
    {
        $this->auth();
        parse_str($this->query, $output);
        if (isset($_POST['update']) && isset($_POST['_METHOD'])) {
            if ($_POST['_METHOD'] === 'PATCH') {
                $this->model->complete();
                $this->redirect('/');
            }
        }
    }

    public function deleteAction()
    {
        $this->auth();
        if (isset($_POST['delete']) && isset($_POST['_METHOD'])) {
            if ($_POST['_METHOD'] === 'DELETE') {
                $this->model->delete();
                $this->redirect('/');
            }
        }
    }
}
