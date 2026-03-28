<?php

namespace App\Filament\Employee\Widgets;

use App\Enums\EvaluationStatuses;
use App\Models\Evaluation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EmployeeQAStatsWidget extends BaseWidget
{
    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $employee = Auth::user()?->employee;

        if (! $employee) {
            return [];
        }

        $totalEvaluations = Evaluation::query()
            ->where('employee_id', $employee->id)
            ->whereNotIn('status', [EvaluationStatuses::Draft])
            ->count();

        if ($totalEvaluations === 0) {
            return [
                Stat::make('Total evaluations', 0)
                    ->description('No evaluations received yet')
                    ->color('secondary'),
            ];
        }

        $passingEvaluations = Evaluation::query()
            ->where('employee_id', $employee->id)
            ->whereNotIn('status', [EvaluationStatuses::Draft])
            ->whereColumn('success_percentage', '>=', 'threshold_percentage')
            ->count();

        $passingPercentage = round(($passingEvaluations / $totalEvaluations) * 100, 1);

        $acceptedCount = Evaluation::query()
            ->where('employee_id', $employee->id)
            ->where('status', EvaluationStatuses::AcceptedClosed)
            ->count();

        $acceptedPercentage = round(($acceptedCount / $totalEvaluations) * 100, 1);

        $latestEvaluation = Evaluation::query()
            ->where('employee_id', $employee->id)
            ->whereNotIn('status', [EvaluationStatuses::Draft])
            ->latest('evaluation_date')
            ->first();

        $stats = [
            Stat::make('Total evaluations', $totalEvaluations)
                ->description('All published evaluations')
                ->color('info'),

            Stat::make('Passing rate', "{$passingPercentage}%")
                ->description("{$passingEvaluations} of {$totalEvaluations} above threshold")
                ->color($passingPercentage >= 80 ? 'success' : ($passingPercentage >= 60 ? 'warning' : 'danger')),

            Stat::make('QA acceptance rate', "{$acceptedPercentage}%")
                ->description("{$acceptedCount} accepted and closed")
                ->color($acceptedPercentage >= 70 ? 'success' : 'warning'),
        ];

        if ($latestEvaluation !== null) {
            $stats[] = Stat::make('Last evaluation score', "{$latestEvaluation->success_percentage}%")
                ->description(Carbon::parse($latestEvaluation->evaluation_date)->format('M d, Y'))
                ->color($latestEvaluation->success_percentage >= $latestEvaluation->threshold_percentage ? 'success' : 'danger');
        }

        return $stats;
    }
}
