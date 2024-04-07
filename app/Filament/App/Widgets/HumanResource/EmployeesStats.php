<?php

namespace App\Filament\App\Widgets\HumanResource;

use App\Models\Employee;
use App\Enums\EmployeeStatus;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class EmployeesStats extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        return [
            Stat::make(
                'Employees Active',
                Employee::query()
                    ->current()
                    ->when($this->filters['site'], function ($employeeQuery) {
                        $employeeQuery->whereHas('site', function ($siteQuery) {
                            $siteQuery->where('id', $this->filters['site']);
                        });
                    })
                    ->count()
            )
                ->description('Employees that are currently working ')
                ->color('success'),
            Stat::make(
                'Employees Terminated',
                Employee::query()
                    ->inactive()
                    ->when($this->filters['site'], function ($employeeQuery) {
                        $employeeQuery->whereHas('site', function ($siteQuery) {
                            $siteQuery->where('id', $this->filters['site']);
                        });
                    })
                    ->count()
            )
                ->description('Employees that are no longer working with us')
                ->color('danger'),
            Stat::make(
                'Employees Suspended',
                Employee::query()
                    ->suspended()
                    ->when($this->filters['site'], function ($employeeQuery) {
                        $employeeQuery->whereHas('site', function ($siteQuery) {
                            $siteQuery->where('id', $this->filters['site']);
                        });
                    })
                    ->count()
            )
                ->description('Employees with a suspension reported')
                ->color('warning'),
        ];
    }
}
