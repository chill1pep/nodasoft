<?php

namespace app\database;

use app\database\exceptions\StatementException;

class Statement
{
    private \PDOStatement $stmt;

    public function __construct(\PDOStatement $stmt)
    {
        $this->stmt = $stmt;
    }

    public function bindParam(string $param, $value, $type = \PDO::PARAM_STR): void
    {
        $result = $this->stmt->bindParam($param, $value, $type);
        if (!$result) {
            throw new StatementException();
        }
    }

    public function fetchAll(int $mode = \PDO::FETCH_ASSOC): array
    {
        $result = $this->stmt->fetchAll($mode);
        if ($result === false) {
            throw new StatementException();
        }
        return $result;
    }

    public function execute(?array $params = null): void
    {
        $result = $this->stmt->execute($params);
        if (!$result) {
            throw new StatementException();
        }
    }
}