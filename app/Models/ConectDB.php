<?php

namespace App\Models;

use PDO;
use PDOException;

class ConectDB
{
    private static $instance;
    private $connection;

    private function __construct()
    {
        $host = env('DB_HOST');
        $user = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $DB = env('DB_DATABASE');
        try {
            $dsn = "mysql:host=" . $host . ";dbname=$DB";
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );
            $this->connection = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function getConnection()
    {
        return $this->connection;
    }
}
