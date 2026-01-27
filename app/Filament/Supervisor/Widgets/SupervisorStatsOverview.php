<?php

namespace App\Filament\Supervisor\Widgets;

use App\Enums\EmployeeStatuses;
use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class SupervisorStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Team Snapshot';

    protected function getStats(): array
    {
        $supervisor = Auth::user()?->supervisor;

        $employees = Employee::query()
            ->whereHas('supervisor', function ($query) use ($supervisor) {
                $query->where('id', $supervisor?->id);
            });

        $totalAssigned = (clone $employees)
            ->whereIn('status', [
                EmployeeStatuses::Hired,
                EmployeeStatuses::Suspended,
            ])
            ->count();

        $activeEmployees = (clone $employees)
            ->where('status', EmployeeStatuses::Hired)
            ->count();

        $suspendedEmployees = (clone $employees)
            ->where('status', EmployeeStatuses::Suspended)
            ->count();

        return [
            Stat::make('Total Employees', $totalAssigned)
                ->color('primary'),
            Stat::make('Active Employees', $activeEmployees)
                ->color('success'),
            Stat::make('Suspended Employees', $suspendedEmployees)
                ->color('warning'),
        ];
    }
}
