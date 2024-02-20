<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Department;

class DepartmentService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('department_list', function () {
            return Department::orderBy('name')->pluck('name', 'id');
        });
    }
}
