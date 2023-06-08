<?php

namespace app\table;

class UserTable extends BaseTable
{
    public const FId = 'id';
    public const FName = 'name';
    public const FLastName = 'lastName';
    public const FFrom = 'from';
    public const FAge = 'age';
    public const FKey = 'key';

    /** @inheritDoc */
    public static function getTableName(): string
    {
        return 'Users';
    }
}