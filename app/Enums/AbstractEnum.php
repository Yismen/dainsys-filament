<?php

namespace App\Enums;


use ReflectionClass;

abstract class AbstractEnum
{
    public static function items(): array
    {
        $class = new ReflectionClass(static::class);

        return $class->getConstants();
    }

    public static function values()
    {
        return array_values(self::items());
    }

    public static function all()
    {
        $array = [];
        foreach (self::items() as $key => $value) {
            $array[$value] = $value;
        }

        return $array;
    }
}