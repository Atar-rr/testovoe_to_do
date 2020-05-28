<?php

namespace App\model;

use App\core\Model;
use App\lib\TaskValidator;

class Task extends Model
{
    private $sth;
    private $tasks = [];

    public function show($page, $sort)
    {
        $chunk = 3;
        $offset = ($page - 1) * $chunk;
        $countPage = ceil($this->countRow() / $chunk);
        $howSort = $this->selectSort($sort);

        $sqlParams = ['offset' => $offset, 'chunk' => $chunk];
        $sql = "SELECT * FROM task ORDER BY $howSort LIMIT :offset, :chunk";
        $this->sth = $this->execute($sql, $sqlParams);
        $this->makeSafe();

        $params = ['task' => $this->tasks, 'page' => $page, 'countPage' => $countPage, 'sort' => $sort];

        return $params;
    }

    public function create()
    {
        $task = $_POST['task'];
        $validator = new TaskValidator($task);
        $error = $validator->validate();
        $params = ['task' => $task, 'error' => $error];

        if (!count($error)) {
            $sql = "INSERT INTO task (name, email, note) VALUES (:name, :email, :note)";
            $this->execute($sql, $task);

            return $params;
        }

        return $params;
    }

    public function update()
    {
        $task = $_POST['task'];
        $statusUpdate = 1;

        $sql = "UPDATE task SET note=:note, status_update=:statusUpdate WHERE id=:id";
        $sqlParams = ['id' => $task['id'], 'note' => $task['note'],'statusUpdate' => $statusUpdate];
        $this->execute($sql, $sqlParams);
    }

    public function complete()
    {
        $task = $_POST['task'];
        if (isset($task['status_complete'])) {
            $task['status_complete'] = 1;
        } else {
            $task['status_complete'] = 0;
        }

        $sql = "UPDATE task SET status_complete=:statusComplete WHERE id=:id";
        $sqlParams = ['id' => $task['id'],'statusComplete' => $task['status_complete']];
        $this->execute($sql, $sqlParams);
    }

    public function edit($id)
    {
        $sql = "SELECT * FROM task WHERE id=:id";
        $sqlParams = ['id' => $id];
        $sth = $this->execute($sql, $sqlParams);
        $row = $sth->fetch(\PDO::FETCH_ASSOC);

        return ['task' => $row];
    }

    private function selectSort($i)
    {
        switch ($i) {
            case 1:
                return 'email';
            case 2:
                return 'email DESC';
            case 3:
                return 'name';
            case 4:
                return 'name DESC';
            case 5:
                return 'status_complete';
            case 6:
                return 'status_complete DESC';
            default: return 'id';
        }
    }

    public function delete()
    {
        $task = $_POST['task'];
        $sql = "DELETE FROM task WHERE id=:id";
        $sqlParams = ['id' => $task['id']];
        $this->execute($sql, $sqlParams);
    }

    private function makeSafe()
    {
        while ($row = $this->sth->fetch()) {
            $this->tasks[] = [
                'id' => htmlspecialchars($row['id']),
                'name' => htmlspecialchars($row['name']),
                'email' => htmlspecialchars($row['email']),
                'note' => htmlspecialchars($row['note']),
                'status_complete' => htmlspecialchars($row['status_complete']),
                'status_update' => htmlspecialchars($row['status_update'])
            ];
        }
    }

    private function countRow()
    {
        $sql = "SELECT count(id) FROM task";
        $sth = $this->execute($sql);

        return $sth->fetch(\PDO::FETCH_ASSOC)['count(id)'];
    }
}