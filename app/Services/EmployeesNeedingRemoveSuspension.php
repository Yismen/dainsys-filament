<?php

namespace App\Services;

use App\Models\Employee;

class EmployeesNeedingRemoveSuspension implements ServicesContract
{
    public static function list()
    {
        return Employee::query()
            ->suspended()
            ->with('suspensions')
            ->whereHas('suspensions', function ($suspensionQuery): void {
                $suspensionQuery->where('starts_at', '>', now())
                    ->orWhere('ends_at', '<', now()->endOfDay());
            })
            ->get();
    }
}
