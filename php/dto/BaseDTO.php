<?php

namespace app\dto;

abstract class BaseDTO
{
    /**
     * Добавил массив свойств, чтобы не дергать рефлексию.
     * @var array
     */
    protected array $row = [];

    public function __construct(array $row)
    {
        $this->fill($row);
    }

    /**
     * Заполнить DTO данными.
     * @param array $row
     * @return $this
     */
    public function fill(array $row): static
    {
        foreach ($this->row as $property => $value) {
            if (!isset($row[$property])
                && (!($this->row[$property] instanceof EmptyFieldDTO))
            ) {
                $this->row[$property] = EmptyFieldDTO::get();
                continue;
            }
            $method = 'set'.ucfirst($property);
            if (method_exists($this, $method)) {
                // Используем сеттер вместо использования свойства для проверки передаваемого типа на уровне php.
                $this->$method($value);
            }
        }
        return $this;
    }

    /**
     * Получить DTO в виде массива.
     * @param bool $isExcludeNotDefined Исключить незаданные поля?
     * @return array
     */
    public function getRow(bool $isExcludeNotDefined = true): array
    {
        $result = [];
        foreach ($this->row as $property => $value) {
            if ($isExcludeNotDefined && $value instanceof EmptyFieldDTO) {
                continue;
            }
            $result[$property] = $value;
        }
        return $result;
    }
}