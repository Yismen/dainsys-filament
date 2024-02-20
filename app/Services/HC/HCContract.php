<?php

namespace App\Services\HC;

use Illuminate\Database\Eloquent\Collection;

interface HCContract
{
    public function count(): Collection;

    public function list(): Collection;
}
