<?php

namespace App\Services;

use App\Enums\EmployeeStatus;
use App\Models\Employee;
use Illuminate\Support\Facades\Cache;

class EmployeesNotInactiveService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('employees_not_inactive_list', function () {
            return Employee::query()
                ->where('status', '<>', EmployeeStatus::Terminated)
                ->orderBy('full_name')
                ->pluck('full_name', 'id');
        });
    }
}
