<?php

namespace App\Services\Attrition;

use Illuminate\Database\Eloquent\Collection;

interface AttritionContract
{
    public function count(): int;

    public function list(): Collection;
}
