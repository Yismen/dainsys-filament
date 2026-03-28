<?php

namespace App\Filament\QA\Widgets;

use App\Enums\EvaluationStatuses;
use App\Enums\QARoles;
use App\Models\Evaluation;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class QAStatsOverview extends StatsOverviewWidget
{
    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $user = Auth::user();
        $last30Days = Carbon::today()->subDays(30);

        $isManager = $user?->hasRole(QARoles::Manager->value);

        $baseQuery = Evaluation::query()
            ->whereDate('evaluation_date', '>=', $last30Days);

        if (! $isManager) {
            $baseQuery->where('evaluator_id', $user?->id);
        }

        $totalEvaluations = (clone $baseQuery)->count();

        $acceptedCount = (clone $baseQuery)
            ->where('status', EvaluationStatuses::AcceptedClosed)
            ->count();

        $disputedCount = (clone $baseQuery)
            ->where('status', EvaluationStatuses::Disputed)
            ->count();

        $acceptedPercentage = $totalEvaluations > 0
            ? round(($acceptedCount / $totalEvaluations) * 100, 1)
            : 0;

        $disputedPercentage = $totalEvaluations > 0
            ? round(($disputedCount / $totalEvaluations) * 100, 1)
            : 0;

        $pendingDisputes = Evaluation::query()
            ->where('status', EvaluationStatuses::Disputed)
            ->count();

        $stats = [
            Stat::make('Evaluations (last 30 days)', $totalEvaluations)
                ->description($isManager ? 'All evaluations' : 'Your evaluations')
                ->color('info'),

            Stat::make('Accepted rate', "{$acceptedPercentage}%")
                ->description("{$acceptedCount} accepted")
                ->color($acceptedPercentage >= 70 ? 'success' : 'warning'),

            Stat::make('Disputed rate', "{$disputedPercentage}%")
                ->description("{$disputedCount} disputed")
                ->color($disputedPercentage > 20 ? 'danger' : ($disputedPercentage > 10 ? 'warning' : 'success')),
        ];

        if ($isManager) {
            $stats[] = Stat::make('Pending disputes', $pendingDisputes)
                ->description('Awaiting manager resolution')
                ->color($pendingDisputes > 0 ? 'warning' : 'success');
        }

        return $stats;
    }
}
