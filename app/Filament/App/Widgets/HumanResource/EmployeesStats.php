<?php

namespace App\Filament\App\Widgets\HumanResource;

use App\Models\Employee;
use App\Enums\EmployeeStatus;
use Illuminate\Database\Eloquent\Builder;
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
                $this->employeesCount(scope: 'current')
            )
                ->description('Employees that are currently working ')
                ->color('success'),
            Stat::make(
                'Employees Terminated',
                $this->employeesCount(scope: 'inactive')
            )
                ->description('Employees that are no longer working with us')
                ->color('danger'),
            Stat::make(
                'Employees Suspended',
                $this->employeesCount(scope: 'suspended')
            )
                ->description('Employees with a suspension reported')
                ->color('warning'),
        ];
    }

    protected function employeesCount(string $scope): int
    {
        return Employee::query()
            ->$scope()
            ->when($this->filters['site'], function ($employeeQuery) {
                $employeeQuery->whereHas('site', function ($siteQuery) {
                    $siteQuery->when(
                        is_array($this->filters['site']),
                        fn ($query) => $query->whereIn('id', $this->filters['site']),
                        fn ($query) => $query->where('id', $this->filters['site'])
                    );
                });
            })->count();
    }
}
