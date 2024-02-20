<?php

namespace App\Services\HC;

use Illuminate\Database\Eloquent\Model;
use App\Models\Position;

class ByPosition extends AbstractHCService
{
    protected function model(): Model
    {
        return new Position();
    }
}
