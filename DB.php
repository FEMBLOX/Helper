<?php

namespace FEMBLOX;

class DB
{
    private static $instance;
    private $connection;

    private function __construct()
    {
        $this->connection = new \PDO(
            "mysql:host=" . "Your Host" . ";
            dbname=" . "Your DB" . ";
            charset=utf8mb4",
            "Your User",
            CONFIG["Password in Config"]
        );
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    function run($sql, $args = null)
    {
        if (!$args) return $this->connection->query($sql);
        $stmt = $this->connection->prepare($sql);
        foreach ($args as $param => $value)
        {
            $stmt->bindValue($param, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt;
    }
}

?>
