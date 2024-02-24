<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Employee;
use App\Enums\EmployeeStatus;

class EmployeesNotInactiveService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('employees_not_inactive_list', function () {
            return Employee::query()
                ->where('status', '<>', EmployeeStatus::Inactive)
                ->orderBy('full_name')
                ->pluck('full_name', 'id');
        });
    }
}
