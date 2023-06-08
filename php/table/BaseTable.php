<?php

namespace app\table;

/**
 * Базовый класс для описания информации рабочей таблицы.
 */
abstract class BaseTable
{
    /**
     * Получить название рабочей таблицы.
     * @return string
     */
    abstract public static function getTableName(): string;
}