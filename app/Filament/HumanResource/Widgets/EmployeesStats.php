<?php

namespace App\Filament\HumanResource\Widgets;

use App\Models\Employee;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

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
        $filtersString = '';
        foreach ($this->pageFilters ?? [] as $key => $value) {
            $filtersString = $filtersString.\implode('_', [
                $key, \is_array($value) ? \implode('_', $value) : $value,
            ]);
        }

        $cacheKey = \implode('_', [
            'count_of_employees_in_status',
            $scope,
            'and_filters',
            $filtersString,
        ]);

        return Cache::rememberForever(
            $cacheKey,
            function () use ($scope) {
                return Employee::query()
                    ->$scope()
                    ->withWhereHas('hires', function ($hiresQuery) {
                        $hiresQuery
                            ->when($this->pageFilters['site'] ?? null, function ($hiresQuery) {
                                $hiresQuery->withWhereHas('site', function ($siteQuery) {
                                    $siteQuery->when(
                                        is_array($this->pageFilters['site']),
                                        fn ($query) => $query->whereIn('id', $this->pageFilters['site']),
                                        fn ($query) => $query->where('id', $this->pageFilters['site'])
                                    );
                                });
                            })
                            ->when($this->pageFilters['project'] ?? null, function ($hiresQuery) {
                                $hiresQuery->withWhereHas('project', function ($projectQuery) {
                                    $projectQuery->when(
                                        is_array($this->pageFilters['project']),
                                        fn ($query) => $query->whereIn('id', $this->pageFilters['project']),
                                        fn ($query) => $query->where('id', $this->pageFilters['project'])
                                    );
                                });
                            })
                            ->when($this->pageFilters['supervisor'] ?? null, function ($hiresQuery) {
                                $hiresQuery->withWhereHas('supervisor', function ($supervisorQuery) {
                                    $supervisorQuery->when(
                                        is_array($this->pageFilters['supervisor']),
                                        fn ($query) => $query->whereIn('id', $this->pageFilters['supervisor']),
                                        fn ($query) => $query->where('id', $this->pageFilters['supervisor'])
                                    );
                                });
                            });
                    })
                    ->count();
            }
        );
    }
}
