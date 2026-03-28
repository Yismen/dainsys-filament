<?php

namespace App\Filament\ProjectExecutive\Widgets;

use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ProjectExecutiveStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Projects Snapshot';

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $managerId = Auth::id();

        $totalAssignedEmployees = Employee::query()
            ->active()
            ->whereHas('project', function ($query) use ($managerId): void {
                $query->where('manager_id', $managerId);
            })
            ->count();

        return [
            Stat::make('Assigned active employees', $totalAssignedEmployees)
                ->color('primary'),
        ];
    }
}
