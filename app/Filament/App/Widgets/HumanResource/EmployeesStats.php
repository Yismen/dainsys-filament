<?php

namespace App\Filament\App\Widgets\HumanResource;

use App\Models\Employee;
use App\Enums\EmployeeStatus;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class EmployeesStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Employees Active', Employee::current()->count())
                ->description('Employees that are currently working ')
                ->color('success'),
            Stat::make('Employees Terminated', Employee::inactive()->count())
                ->description('Employees that are no longer working with us')
                ->color('danger'),
            Stat::make('Employees Suspended', Employee::suspended()->count())
                ->description('Employees with a suspension reported')
                ->color('warning'),
        ];
    }
}
