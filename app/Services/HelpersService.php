<?php

namespace App\Services;

class HelpersService
{
    public static function strToArray(string $string, ?int $limit = 2): array
    {
        return preg_split(
            '/(:|\||,)/',
            $string,
            $limit,
            PREG_SPLIT_NO_EMPTY
        );
    }
}
