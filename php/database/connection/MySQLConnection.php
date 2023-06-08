<?php

namespace app\database\connection;

class MySQLConnection implements IConnection
{
    private string $host;
    private string $username;
    private string $password;
    private string $database;
    private ?array $options;

    public function __construct(
        string $host,
        string $username,
        string $password,
        string $database,
        ?array $options = null,
    ) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->options = $options;
    }

    public function connect(): \PDO
    {
        return new \PDO(
            "mysql:host=$this->host;dbname=$this->database",
            $this->username,
            $this->password,
            $this->options,
        );
    }
}