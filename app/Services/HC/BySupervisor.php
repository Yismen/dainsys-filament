<?php

namespace App\Services\HC;

use Illuminate\Database\Eloquent\Model;
use App\Models\Supervisor;

class BySupervisor extends AbstractHCService
{
    protected function model(): Model
    {
        return new Supervisor();
    }
}
