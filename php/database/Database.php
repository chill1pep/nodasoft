<?php

namespace app\database;

use app\database\connection\IConnection;
use app\database\exceptions\DatabaseException;
use app\database\exceptions\StatementException;
use app\database\exceptions\TransactionException;

/**
 * Класс для работы с БД.
 */
class Database
{
    private \PDO $pdo;

    public function __construct(IConnection $connection)
    {
        $this->pdo = $connection->connect();
    }

    /**
     * Получить Statement из запроса.
     * @param string $query
     * @param array $options
     * @return Statement
     * @throws StatementException
     */
    public function prepare(string $query, array $options = []): Statement
    {
        $stmt = $this->pdo->prepare($query, $options);
        if ($stmt === false) {
            throw new StatementException();
        }
        return new Statement($stmt);
    }

    /**
     * Получить Id последней вставленной записи данного подключения.
     * @throws DatabaseException
     */
    public function getLastInsertId(): string
    {
        $id = $this->pdo->lastInsertId();
        if ($id === false) {
            throw new DatabaseException();
        }
        return $id;
    }

    /**
     * Начать транзакцию.
     * @throws TransactionException
     */
    public function beginTransaction(): void
    {
        $result = $this->pdo->beginTransaction();
        if (!$result) {
            throw new TransactionException();
        }
    }

    /**
     * Применить транзакцию.
     * @throws TransactionException
     */
    public function commit(): void
    {
        $result = $this->pdo->commit();
        if (!$result) {
            throw new TransactionException();
        }
    }

    /**
     * Откатить транзакцию
     * @throws TransactionException
     */
    public function rollBack(): void
    {
        $result = $this->pdo->rollBack();
        if (!$result) {
            throw new TransactionException();
        }
    }
}