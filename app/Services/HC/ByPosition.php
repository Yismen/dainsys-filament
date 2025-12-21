<?php

namespace App\Services\HC;

use App\Models\Position;
use Illuminate\Database\Eloquent\Model;

class ByPosition extends AbstractHCService
{
    protected function model(): Model
    {
        return new Position;
    }
}
