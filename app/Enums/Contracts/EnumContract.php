<?php

namespace App\Enums\Contracts;

interface EnumContract
{
    public static function toArray(): array;

    public static function names(): array;

    public static function values(): array;
}
