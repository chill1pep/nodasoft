<?php

namespace app\dao;

use app\database\exceptions\DatabaseException;
use app\database\exceptions\StatementException;
use app\dto\UserDTO;
use app\table\UserTable;

class UserDAO extends BaseDAO
{
    /**
     * Создать пользователей.
     * @param UserDTO[] $users
     * @return array Идентификаторы пользователей.
     * @throws \Throwable
     */
    public function createUsers(array $users): array
    {
        $ids = [];
        $this->getDatabase()->beginTransaction();
        try {
            foreach ($users as $user) {
                $ids[] = $this->createUser($user);
            }
            $this->getDatabase()->commit();
        } catch (\Throwable $e) {
            $this->getDatabase()->rollBack();
            throw $e;
        }

        return $ids;
    }

    /**
     * Создать пользователя.
     * @param UserDTO $user
     * @return string
     * @throws DatabaseException
     * @throws StatementException
     * @throws \InvalidArgumentException
     */
    public function createUser(UserDTO $user): string
    {
        $id = $this->insert($user);
        $user->setId($id);
        return $id;
    }

    /**
     * Получить пользователей, у которых возраст больше заданного.
     * @param int $ageFrom
     * @param int|null $limit
     * @param int|null $offset
     * @return UserDTO[]
     */
    public function getUsers_OlderThenAge(int $ageFrom, ?int $limit = 10, ?int $offset = null): array
    {
        $table = $this->getTableName();
        $fAge = UserTable::FAge;
        $fId = UserTable::FId;
        $sql = "SELECT * FROM $table WHERE $fAge > :age ORDER BY $fId";
        if ($limit !== null) {
            // Можно подобные конструкции вынести в отдельный метод или какой-нибудь QueryBuilder.
            $sql .= " LIMIT :limit";
            if ($offset !== null) {
                $sql .= " OFFSET :offset";
            }
        }
        $stmt = $this->getDatabase()->prepare($sql);
        $stmt->bindParam(':age', $ageFrom, \PDO::PARAM_INT);
        if ($limit !== null) {
            $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
            if ($offset !== null) {
                $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
            }
        }
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $result[] = new UserDTO($row);
        }
        return $result;
    }

    /**
     * Возвращает пользователей по списку имен: ищет прямые совпадения.
     * @param array $names
     * @param int|null $limit
     * @param int|null $offset
     * @return UserDTO[]
     */
    public function getUsers_ByNames(array $names, ?int $limit = null, ?int $offset = null): array
    {
        if ($names === []) {
            return [];
        }
        $bindings = [];
        $placeholders = [];
        foreach (array_unique($names) as $index => $name) {
            $paramName = ":name_" . $index;
            $placeholders[] = $paramName;
            $bindings[$paramName] = $name;
        }
        $table = $this->getTableName();
        $fName = UserTable::FName;
        $fId = UserTable::FId;
        $sql = "
        SELECT * FROM $table WHERE $fName IN (" . implode(',', $placeholders) . ")
        ORDER BY $fId
        ";
        if ($limit !== null) {
            // Можно подобные конструкции вынести в отдельный метод или какой-нибудь QueryBuilder.
            $sql .= " LIMIT :limit";
            if ($offset !== null) {
                $sql .= " OFFSET :offset";
            }
        }
        $stmt = $this->getDatabase()->prepare($sql);
        if ($limit !== null) {
            $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
            if ($offset !== null) {
                $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
            }
        }
        $stmt->execute($bindings);
        $rows = $stmt->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $result[] = new UserDTO($row);
        }
        return $result;
    }
}