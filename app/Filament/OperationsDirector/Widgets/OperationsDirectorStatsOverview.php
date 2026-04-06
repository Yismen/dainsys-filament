<?php

namespace App\Filament\OperationsDirector\Widgets;

use App\Filament\OperationsDirector\Widgets\Concerns\InteractsWithProjectAndClientFilters;
use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OperationsDirectorStatsOverview extends StatsOverviewWidget
{
    use InteractsWithProjectAndClientFilters;

    protected ?string $heading = 'Projects Snapshot';

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $projectIds = $this->getFilteredProjectIds();

        $totalAssignedEmployees = Employee::query()
            ->active()
            ->when(
                $projectIds !== [],
                fn ($query) => $query->whereIn('project_id', $projectIds),
            )
            ->when(
                ($projectIds === []) && $this->hasProjectOrClientFiltersApplied(),
                fn ($query) => $query->whereRaw('1 = 0'),
            )
            ->count();

        return [
            Stat::make('Assigned active employees', $totalAssignedEmployees)
                ->color('primary'),
        ];
    }
}
