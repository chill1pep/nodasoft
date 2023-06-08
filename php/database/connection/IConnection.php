<?php

namespace app\database\connection;

/**
 * Интерфейс описывает подключение к реляционной СУБД.
 */
interface IConnection
{
    /**
     * @return \PDO
     */
    public function connect(): \PDO;
}