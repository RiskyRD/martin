<?php

namespace Core\Database;

class DB
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $config = require __DIR__ . '/../../config/db.php';

        $driver = $config['driver'];
        $host = $config['host'];
        $dbname = $config['database'];
        $username = $config['username'];
        $password = $config['password'];

        try {
            $this->connection = new \PDO(
                "$driver:host=$host;dbname=$dbname",
                $username,
                $password
            );
        } catch (\PDOException $e) {

            if ($e->getCode() == 1049) {

                $tempPdo = new \PDO(
                    "$driver:host=$host",
                    $username,
                    $password
                );

                $tempPdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");

                $this->connection = new \PDO(
                    "$driver:host=$host;dbname=$dbname",
                    $username,
                    $password
                );
            } else {
                throw $e;
            }
        }
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