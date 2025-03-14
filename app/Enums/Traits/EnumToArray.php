<?php

namespace App\Enums\Traits;

trait EnumToArray
{
    public static function toArray(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
