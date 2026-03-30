<?php

namespace App\Filament\Employee\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SPHTrendWidget extends ChartWidget
{
    protected ?string $heading = 'Sales Per Hour (SPH) vs Goal (Last 14 Days)';

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    public function getData(): array
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $startDate = Carbon::now()->subDays(14);
        $endDate = Carbon::now();

        $dailyMetrics = Cache::remember(
            "employee_{$employee->id}_sph_trend_{$startDate->toDateString()}_{$endDate->toDateString()}",
            now()->addHours(3),
            function () use ($employee, $startDate, $endDate): array {
                return $employee->productions()
                    ->whereBetween('date', [$startDate, $endDate])
                    ->orderBy('date')
                    ->get()
                    ->groupBy(fn ($record) => $record->date->format('M d'))
                    ->map(function ($productionGroup, string $date): array {
                        $totalConversions = (float) $productionGroup->sum('conversions');
                        $totalProductionTime = (float) $productionGroup->sum('production_time');
                        $goalConversions = (float) $productionGroup->sum('conversions_goal');

                        return [
                            'date' => $date,
                            'actual_sph' => $totalProductionTime > 0 ? round($totalConversions / $totalProductionTime, 2) : 0,
                            'goal_sph' => $totalProductionTime > 0 ? round($goalConversions / $totalProductionTime, 2) : 0,
                        ];
                    })
                    ->values()
                    ->all();
            }
        );

        $dates = [];
        $actualSPH = [];
        $goalSPH = [];

        foreach ($dailyMetrics as $metric) {
            $dates[] = $metric['date'];
            $goalSPH[] = $metric['goal_sph'];
            $actualSPH[] = $metric['actual_sph'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Actual SPH',
                    'data' => $actualSPH,
                    'borderColor' => '#06b6d4',
                    'backgroundColor' => 'rgba(6, 182, 212, 0.1)',
                    'tension' => 0.3,
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'SPH Goal',
                    'data' => $goalSPH,
                    'borderColor' => '#8b5cf6',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                    'tension' => 0.3,
                    'borderWidth' => 2,
                    'borderDash' => [5, 5],
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
