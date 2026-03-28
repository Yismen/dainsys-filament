<?php

namespace App\Filament\OperationsDirector\Widgets;

use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OperationsDirectorStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Projects Snapshot';

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $totalAssignedEmployees = Employee::query()
            ->active()
            ->count();

        return [
            Stat::make('Assigned active employees', $totalAssignedEmployees)
                ->color('primary'),
        ];
    }
}
