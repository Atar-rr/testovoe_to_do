<?php

namespace App\lib;

class AdminValidator
{
    private $admin = [];
    private $errors = [];

    public function __construct($data)
    {
        $this->admin['login'] = $data['login'] ?? '';
        $this->admin['password'] = $data['password'] ?? '';
    }

    public function validate($data)
    {
        $this->validateLogin();
        $this->validatePassword($data['password']);

        return $this->errors;
    }

    private function validateLogin()
    {
        if (empty($this->getLogin())) {
            $this->errors['login'] = "Поле не может быть пустым";
        }
    }

    private function validatePassword($password)
    {
        if (empty($this->getPassword())) {
            $this->errors['password'] = "Поле не может быть пустым";
        } elseif ($this->getPassword() !== hash('whirlpool', $password)) {
            $this->errors['password'] = "Неверный пароль";
        }
    }

    public function getLogin()
    {
        return $this->admin['login'];
    }

    public function getPassword()
    {
        return $this->admin['password'];
    }
}
