<?php

namespace App\core;

abstract class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = Db::getConnection();
    }

    public function execute($sql, $params = [])
    {
        $sth = $this->db->prepare($sql);

        $sth->execute($params);

        return $sth;
    }
}
