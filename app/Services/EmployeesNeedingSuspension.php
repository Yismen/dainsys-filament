<?php

namespace App\Services;

use App\Models\Employee;

class EmployeesNeedingSuspension implements ServicesContract
{
    public static function list()
    {
        return Employee::current()->hasActiveSuspension()->get();
    }
}
