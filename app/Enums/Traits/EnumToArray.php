<?php

namespace App\Enums\Traits;

use Filament\Support\Contracts\HasLabel;

trait EnumToArray
{
    public static function toArray(): array
    {
        $array = [];

        foreach (self::cases() as $case) {
            $array[$case->value] = $case instanceof HasLabel ? $case->getLabel() : $case->name;
        }

        return $array;
    }
}
