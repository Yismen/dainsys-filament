<?php

namespace App\Filament\ProjectExecutive\Widgets;

use App\Enums\EvaluationStatuses;
use App\Filament\ProjectExecutive\Widgets\Concerns\InteractsWithProjectFilter;
use App\Models\Evaluation;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ProjectExecutiveQAStatsWidget extends StatsOverviewWidget
{
    use InteractsWithProjectFilter;

    protected ?string $heading = 'QA Overview (Your Projects)';

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $managerId = Auth::id();
        $selectedProjectIds = $this->getSelectedProjectIdsFromPageFilters();

        if (! $managerId) {
            return [];
        }

        $baseQuery = Evaluation::query()
            ->whereHas('employee.project', function ($query) use ($managerId, $selectedProjectIds): void {
                $query->where('manager_id', $managerId)
                    ->when(
                        $selectedProjectIds !== [],
                        fn ($builder) => $builder->whereIn('id', $selectedProjectIds),
                    );
            });

        $totalPublished = (clone $baseQuery)
            ->whereNot('status', EvaluationStatuses::Draft)
            ->count();

        $acceptedCount = (clone $baseQuery)
            ->where('status', EvaluationStatuses::AcceptedClosed)
            ->count();

        $disputedCount = (clone $baseQuery)
            ->where('status', EvaluationStatuses::Disputed)
            ->count();

        $passingCount = (clone $baseQuery)
            ->whereNot('status', EvaluationStatuses::Draft)
            ->whereColumn('success_percentage', '>=', 'threshold_percentage')
            ->count();

        $passingRate = $totalPublished > 0 ? round(($passingCount / $totalPublished) * 100, 1) : 0;

        return [
            Stat::make('Project evaluations', $totalPublished)
                ->description('Published evaluations')
                ->color('info'),
            Stat::make('Passing rate', "{$passingRate}%")
                ->description("{$passingCount} above threshold")
                ->color($passingRate >= 80 ? 'success' : ($passingRate >= 60 ? 'warning' : 'danger')),
            Stat::make('Accepted', $acceptedCount)
                ->description('Accepted and closed')
                ->color('success'),
            Stat::make('Disputed', $disputedCount)
                ->description('Awaiting resolution')
                ->color($disputedCount > 0 ? 'warning' : 'success'),
        ];
    }
}
