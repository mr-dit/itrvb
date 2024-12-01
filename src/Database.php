<?php

namespace App;

class Database 
{
    private \PDO $pdo;

    public function __construct() 
    {
        $this->pdo = new \PDO('sqlite:' . dirname(__DIR__) . '/database.sqlite');
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    public function getPdo(): \PDO 
    {
        return $this->pdo;
    }
}
