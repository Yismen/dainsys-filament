<?php

namespace App\Services\HC;

use App\Models\Project;
use Illuminate\Database\Eloquent\Model;

class ByProject extends AbstractHCService
{
    protected function model(): Model
    {
        return new Project;
    }
}
