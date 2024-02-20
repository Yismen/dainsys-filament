<?php

namespace App\Services;

use App\Models\Employee;

class EmployeesNeedingRemoveSuspension implements ServicesContract
{
    public static function list()
    {
        return Employee::suspended()->missingActiveSuspension()->get();
    }
}
