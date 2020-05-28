<?php

namespace App\core;

class Db
{
    public static function getConnection()
    {
        $paramsPath = __DIR__ . "/../config/db.php";
        $params = include $paramsPath;

        try {
            $dsn = "mysql:host=$params[host];dbname=$params[dbname];charset=utf8";
            $db = new \PDO($dsn, $params['user'], $params['password']);
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            return $db;
        } catch (\PDOException $e) {
            echo "Подключение не удалось: " . $e->getMessage();
            file_put_contents(__DIR__ . "/../log/db_error", $e->getMessage() . "\n", FILE_APPEND);
            die();
        }
    }
}
