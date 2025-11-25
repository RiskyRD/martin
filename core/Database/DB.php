<?php

namespace Core\Database;

class DB
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $config = require __DIR__ . '/../../config/db.php';
        $this->connection = new \PDO(
            "{$config['driver']}:host={$config['host']};dbname={$config['database']}",
            $config['username'],
            $config['password']
        );
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
