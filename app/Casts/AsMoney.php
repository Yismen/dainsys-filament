<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class AsMoney implements CastsAttributes
{
    public function get(
        Model $model,
        string $key,
        mixed $value,
        array $attributes
    ): float {
        return $value / 100;
    }

    public function set(
        Model $model,
        string $key,
        mixed $value,
        array $attributes): float
    {
        return $value * 100;
    }
}
