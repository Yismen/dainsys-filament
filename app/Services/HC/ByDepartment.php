<?php

namespace App\Services\HC;

use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class ByDepartment extends AbstractHCService
{
    protected function model(): Model
    {
        return new Department();
    }
}
