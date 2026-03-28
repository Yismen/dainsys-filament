<?php

namespace App\Filament\Supervisor\Widgets;

use App\Enums\EvaluationStatuses;
use App\Models\Evaluation;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class SupervisorQAStatsWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'QA Overview (Your Team)';

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $supervisor = Auth::user()?->supervisor;

        if (! $supervisor) {
            return [];
        }

        $baseQuery = Evaluation::query()
            ->whereHas('employee', function ($query) use ($supervisor): void {
                $query->where('supervisor_id', $supervisor->id);
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
            Stat::make('Team evaluations', $totalPublished)
                ->description('Published evaluations')
                ->color('info'),
            Stat::make('Passing rate', "{$passingRate}%")
                ->description("{$passingCount} above threshold")
                ->color($passingRate >= 80 ? 'success' : ($passingRate >= 60 ? 'warning' : 'danger')),
            Stat::make('Accepted', $acceptedCount)
                ->description('Accepted and closed')
                ->color('success'),
            Stat::make('Disputed', $disputedCount)
                ->description('Pending resolution')
                ->color($disputedCount > 0 ? 'warning' : 'success'),
        ];
    }
}
