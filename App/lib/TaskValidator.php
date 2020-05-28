<?php

namespace App\lib;

class TaskValidator
{
    private $task = [];
    private $errors = [];

    public function __construct($data)
    {
        $this->task['email'] = $data['email'] ?? '';
        $this->task['name'] = $data['name'] ?? '';
        $this->task['note'] = $data['note'] ?? '';
    }

    public function validate()
    {
        $this->validateName();
        $this->validateNote();
        $this->validateEmail();
        return $this->errors;
    }

    private function validateEmail()
    {
        if (empty($this->task['email'])) {
            $this->errors['email'] = "Поле не может быть пустым";
        } elseif (!filter_var($this->task['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "Некорректный e-mail адрес";
        }
    }

    private function validateName()
    {
        if (empty($this->task['name'])) {
            $this->errors['name'] = "Поле не может быть пустым";
        }
    }

    private function validateNote()
    {
        if (empty($this->task['note'])) {
            $this->errors['note'] = "Поле не может быть пустым";
        }
    }
}
