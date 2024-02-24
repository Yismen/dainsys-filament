<?php

namespace App\Enums\Traits;

trait AsEnum
{
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value', 'value');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'value');
    }
}
