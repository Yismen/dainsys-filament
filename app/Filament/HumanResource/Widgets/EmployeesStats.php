<?php

namespace App\Filament\HumanResource\Widgets;

use App\Enums\EmployeeStatuses;
use App\Filament\HumanResource\Resources\Employees\EmployeeResource;
use App\Models\Employee;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class EmployeesStats extends BaseWidget
{
    use InteractsWithPageFilters;

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make(
                'Employees Active',
                $this->employeesCount(scope: 'current')
            )
                ->description('Employees that are currently working ')
                ->color('success')
                ->url(function () {
                    try {
                        return EmployeeResource::getUrl('index', [
                            'filters' => [
                                'status' => ['value' => EmployeeStatuses::Hired->value],
                            ],
                        ]);
                    } catch (\Exception $e) {
                        return null;
                    }
                }),
            Stat::make(
                'Employees Terminated',
                $this->employeesCount(scope: 'inactive')
            )
                ->description('Employees that are no longer working with us')
                ->color('danger')
                ->url(function () {
                    try {
                        return EmployeeResource::getUrl('index', [
                            'filters' => [
                                'status' => ['value' => EmployeeStatuses::Terminated->value],
                            ],
                        ]);
                    } catch (\Exception $e) {
                        return null;
                    }
                }),
            Stat::make(
                'Employees Suspended',
                $this->employeesCount(scope: 'suspended')
            )
                ->description('Employees with a suspension reported')
                ->color('warning')
                ->url(function () {
                    try {
                        return EmployeeResource::getUrl('index', [
                            'filters' => [
                                'status' => ['value' => EmployeeStatuses::Suspended->value],
                            ],
                        ]);
                    } catch (\Exception $e) {
                        return null;
                    }
                }),
        ];
    }

    protected function employeesCount(string $scope): int
    {
        $filtersString = $this->buildFiltersString();

        $cacheKey = implode('_', [
            'count_of_employees_in_status',
            $scope,
            'filters',
            $filtersString,
        ]);

        return Cache::rememberForever(
            $cacheKey,
            function () use ($scope) {
                return Employee::query()
                    ->$scope()
                    ->when($this->pageFilters['site'] ?? null, function ($siteQuery): void {
                        $siteQuery->withWhereHas('site', function ($siteQuery): void {
                            $siteQuery->when(
                                is_array($this->pageFilters['site']),
                                fn ($query) => $query->whereIn('id', $this->pageFilters['site']),
                                fn ($query) => $query->where('id', $this->pageFilters['site'])
                            );
                        });
                    })
                    ->when($this->pageFilters['project'] ?? null, function ($hiresQuery): void {
                        $hiresQuery->withWhereHas('project', function ($projectQuery): void {
                            $projectQuery->when(
                                is_array($this->pageFilters['project']),
                                fn ($query) => $query->whereIn('id', $this->pageFilters['project']),
                                fn ($query) => $query->where('id', $this->pageFilters['project'])
                            );
                        });
                    })
                    ->when($this->pageFilters['supervisor'] ?? null, function ($hiresQuery): void {
                        $hiresQuery->withWhereHas('supervisor', function ($supervisorQuery): void {
                            $supervisorQuery->when(
                                is_array($this->pageFilters['supervisor']),
                                fn ($query) => $query->whereIn('id', $this->pageFilters['supervisor']),
                                fn ($query) => $query->where('id', $this->pageFilters['supervisor'])
                            );
                        });
                    })
                    ->count();
            }
        );
    }

    protected function buildFiltersString(): string
    {
        $filtersString = '';
        foreach ($this->pageFilters ?? [] as $key => $value) {
            $filtersString .= implode('_', [
                $key,
                is_array($value) ? implode('_', $value) : $value,
            ]);
        }

        return $filtersString ?: 'no_filters';
    }
}
