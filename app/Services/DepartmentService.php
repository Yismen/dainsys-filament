<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Support\Facades\Cache;

class DepartmentService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('department_list', function () {
            return Department::orderBy('name')->pluck('name', 'id');
        });
    }
}
