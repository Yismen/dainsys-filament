<?php

namespace App\Services\HC;

use Illuminate\Database\Eloquent\Model;
use App\Models\Project;

class ByProject extends AbstractHCService
{
    protected function model(): Model
    {
        return new Project();
    }
}
