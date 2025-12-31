<?php

namespace App\Services;

use App\Enums\EmployeeStatuses;
use App\Models\Employee;

class EmployeesNeedingSuspension implements ServicesContract
{
    public static function list()
    {
        return Employee::query()
            ->where('status', EmployeeStatuses::Hired)
            ->with('suspensions')
            ->where(function ($query) {
                $query->whereHas('suspensions', function ($suspensionsQuery) {
                    $suspensionsQuery->active();
                });
            })
            ->get();;
    }
}
