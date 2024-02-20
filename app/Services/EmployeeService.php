<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Support\Facades\Cache;

class EmployeeService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('employees_list', function () {
            return Employee::orderBy('full_name')->pluck('full_name', 'id');
        });
    }
}
