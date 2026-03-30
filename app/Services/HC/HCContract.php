<?php

namespace App\Services\HC;

use Illuminate\Support\Collection;

interface HCContract
{
    public function count(): Collection;

    public function list(): Collection;
}
