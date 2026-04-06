<?php

namespace App\Filament\ProjectExecutive\Widgets;

use App\Filament\ProjectExecutive\Widgets\Concerns\InteractsWithProjectFilter;
use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ProjectExecutiveStatsOverview extends StatsOverviewWidget
{
    use InteractsWithProjectFilter;

    protected ?string $heading = 'Projects Snapshot';

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $managerId = Auth::id();
        $selectedProjectIds = $this->getSelectedProjectIdsFromPageFilters();

        $totalAssignedEmployees = Employee::query()
            ->active()
            ->whereHas('project', function ($query) use ($managerId, $selectedProjectIds): void {
                $query->where('manager_id', $managerId)
                    ->when(
                        $selectedProjectIds !== [],
                        fn ($builder) => $builder->whereIn('id', $selectedProjectIds),
                    );
            })
            ->count();

        return [
            Stat::make('Assigned active employees', $totalAssignedEmployees)
                ->color('primary'),
        ];
    }
}
