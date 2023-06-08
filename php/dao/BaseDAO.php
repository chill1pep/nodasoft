<?php

namespace app\dao;

use app\database\Database;
use app\database\exceptions\DatabaseException;
use app\database\exceptions\StatementException;
use app\database\Statement;
use app\dto\BaseDTO;

abstract class BaseDAO
{
    private Database $database;

    private string $tableName;

    public function __construct(Database $database, string $tableName)
    {
        $this->database = $database;
        $this->tableName = $tableName;
    }

    /**
     * Получить имя рабочей таблицы.
     * @return string
     */
    final protected function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * Получить инстанс связи с БД.
     * @return Database
     */
    protected function getDatabase(): Database
    {
        return $this->database;
    }

    /**
     * Вставить одну запись в таблицу.
     * @param BaseDTO $dto
     * @return string Идентификатор добавленной записи.
     * @throws DatabaseException
     * @throws StatementException
     * @throws \InvalidArgumentException
     */
    protected function insert(BaseDTO $dto): string
    {
        $row = $dto->getRow();
        if ($row === []) {
            // Валидация должна быть раньше, на уровне бизнес логики и не доходить даже до DAO.
            throw new \InvalidArgumentException('Provided empty row bla bla');
        }
        $database = $this->getDatabase();
        $columnsAliasesArr = [];
        $columnsArr = [];
        foreach ($row as $key => $value) {
            $columnsArr[] = $key;
            $columnsAliasesArr[$key] = ':'.$key;
        }
        $columns = implode(', ', $columnsArr);
        $columnsAliases = implode(', ', array_values($columnsAliasesArr));
        $tableName = $this->getTableName();
        $stmt = $database->prepare("
        INSERT INTO $tableName ($columns) VALUES ($columnsAliases)
        ");
        foreach ($columnsAliasesArr as $key => $alias) {
            $stmt->bindParam($alias, $row[$key]);
        }
        $stmt->execute();
        return $database->getLastInsertId();
    }
}