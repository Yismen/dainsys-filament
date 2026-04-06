<?php

namespace App\Filament\OperationsDirector\Widgets;

use App\Enums\EvaluationStatuses;
use App\Filament\OperationsDirector\Widgets\Concerns\InteractsWithProjectAndClientFilters;
use App\Models\Evaluation;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class OperationsDirectorQAStatsWidget extends StatsOverviewWidget
{
    use InteractsWithProjectAndClientFilters;

    protected ?string $heading = 'QA Overview (Organisation)';

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $last30Days = Carbon::today()->subDays(30);
        $projectIds = $this->getFilteredProjectIds();

        $baseQuery = Evaluation::query()
            ->when(
                $projectIds !== [],
                fn ($query) => $query->whereHas('employee', fn ($employeeQuery) => $employeeQuery->whereIn('project_id', $projectIds)),
            )
            ->when(
                ($projectIds === []) && $this->hasProjectOrClientFiltersApplied(),
                fn ($query) => $query->whereRaw('1 = 0'),
            );

        $totalPublished = (clone $baseQuery)
            ->whereNot('status', EvaluationStatuses::Draft)
            ->whereDate('evaluation_date', '>=', $last30Days)
            ->count();

        $acceptedCount = (clone $baseQuery)
            ->where('status', EvaluationStatuses::AcceptedClosed)
            ->whereDate('evaluation_date', '>=', $last30Days)
            ->count();

        $disputedPending = (clone $baseQuery)
            ->where('status', EvaluationStatuses::Disputed)
            ->count();

        $passingCount = (clone $baseQuery)
            ->whereNot('status', EvaluationStatuses::Draft)
            ->whereDate('evaluation_date', '>=', $last30Days)
            ->whereColumn('success_percentage', '>=', 'threshold_percentage')
            ->count();

        $passingRate = $totalPublished > 0 ? round(($passingCount / $totalPublished) * 100, 1) : 0;

        return [
            Stat::make('Evaluations (last 30 days)', $totalPublished)
                ->description('All published')
                ->color('info'),
            Stat::make('Org passing rate', "{$passingRate}%")
                ->description("{$passingCount} above threshold")
                ->color($passingRate >= 80 ? 'success' : ($passingRate >= 60 ? 'warning' : 'danger')),
            Stat::make('Accepted (last 30 days)', $acceptedCount)
                ->description('Accepted and closed')
                ->color('success'),
            Stat::make('Open disputes', $disputedPending)
                ->description('Awaiting resolution')
                ->color($disputedPending > 0 ? 'warning' : 'success'),
        ];
    }
}
