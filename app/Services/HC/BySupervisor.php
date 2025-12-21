<?php

namespace App\Services\HC;

use App\Models\Supervisor;
use Illuminate\Database\Eloquent\Model;

class BySupervisor extends AbstractHCService
{
    protected function model(): Model
    {
        return new Supervisor;
    }
}
