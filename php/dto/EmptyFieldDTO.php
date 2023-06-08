<?php

namespace app\dto;

/**
 * Класс описывает поле, которое отсутствует при выборке.
 */
final class EmptyFieldDTO
{
    private function __construct()
    {
    }

    public static function get(): self
    {
        return new self();
    }
}