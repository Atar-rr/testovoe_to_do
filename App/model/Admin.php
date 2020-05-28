<?php

namespace App\model;

use App\core\Model;
use App\lib\AdminValidator;

class Admin extends Model
{
    public function login()
    {
        $admin = $_POST['admin'];
        $sql = "SELECT id, password, login FROM user WHERE login=:login";
        $sqlParams = ['login' => $admin['login']];
        $sth = $this->execute($sql, $sqlParams);

        $row = $sth->fetch(\PDO::FETCH_ASSOC);
        if ($row) {
            $validator = new AdminValidator($row);
            $errors = $validator->validate($admin);
            if (!count($errors)) {
                $_SESSION['id'] = $row['id'];
            }
        } else {
            $errors['login'] = 'Пользователя с таким логином не существует';
        }

        return ['admin' => $admin, 'error' => $errors];
    }

}