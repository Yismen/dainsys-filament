<?php

namespace App\Services\HC;

use App\Models\Afp;
use Illuminate\Database\Eloquent\Model;

class ByAfp extends AbstractHCService
{
    protected function model(): Model
    {
        return new Afp;
    }
}
