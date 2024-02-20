<?php

namespace App\Services\HC;

use App\Models\Ars;
use Illuminate\Database\Eloquent\Model;

class ByArs extends AbstractHCService
{
    protected function model(): Model
    {
        return new Ars();
    }
}
