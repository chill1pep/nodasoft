<?php

namespace app\dto;

use app\table\UserTable;

/**
 * Не стал оверинжинирить: добавлять __call методы или __get, __set
 * DTO изменяемая.
 */
class UserDTO extends BaseDTO
{
    public function __construct(array $row)
    {
        $this->row = [
            UserTable::FId => EmptyFieldDTO::get(),
            UserTable::FName => EmptyFieldDTO::get(),
            UserTable::FLastName => EmptyFieldDTO::get(),
            UserTable::FFrom => EmptyFieldDTO::get(),
            UserTable::FAge => EmptyFieldDTO::get(),
            UserTable::FKey => EmptyFieldDTO::get(),
        ];
        parent::__construct($row);
    }

    public function getId(): EmptyFieldDTO|string|null
    {
        return $this->row[UserTable::FId];
    }

    public function getName(): EmptyFieldDTO|string|null
    {
        return $this->row[UserTable::FName];
    }

    public function getLastName(): EmptyFieldDTO|string|null
    {
        return $this->row[UserTable::FLastName];
    }

    public function getFrom(): EmptyFieldDTO|string|null
    {
        return $this->row[UserTable::FFrom];
    }

    public function getAge(): EmptyFieldDTO|int|null
    {
        return $this->row[UserTable::FAge];
    }

    /**
     * Непонятно что за settings: судя по всему там реализуется связь 1 к 1 + используется одно поле,
     * поэтому решил просто в текущую таблицу вынести, т.к. mysql плохо с выборкой по json работает.
     * @return EmptyFieldDTO|string|null
     */
    public function getKey(): EmptyFieldDTO|string|null
    {
        return $this->row[UserTable::FKey];
    }

    public function setId(string $val): static
    {
        $this->row[UserTable::FId] = $val;
        return $this;
    }

    public function setName(?string $val): static
    {
        $this->row[UserTable::FName] = $val;
        return $this;
    }

    public function setLastName(?string $val): static
    {
        $this->row[UserTable::FLastName] = $val;
        return $this;
    }

    public function setFrom(?string $val): static
    {
        $this->row[UserTable::FFrom] = $val;
        return $this;
    }

    public function setAge(?int $val): static
    {
        $this->row[UserTable::FAge] = $val;
        return $this;
    }

    public function setKey(?string $val): static
    {
        $this->row[UserTable::FKey] = $val;
        return $this;
    }
}