<?php

namespace App\Services\Attrition;

use Illuminate\Support\Collection;

interface AttritionContract
{
    public function count(): int;

    public function list(): Collection;
}
