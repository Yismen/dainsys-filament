<?php

namespace App\Services\HC;

use App\Models\Department;
use Illuminate\Database\Eloquent\Model;

class ByDepartment extends AbstractHCService
{
    protected function model(): Model
    {
        return new Department;
    }
}
