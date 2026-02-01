<?php

namespace App\Filament\Employee\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class EfficiencyTrendWidget extends ChartWidget
{
    protected ?string $heading = 'Total Hours vs Efficiency (%) (Last 14 Days)';

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

        $productions = Cache::remember(
            "employee_{$employee->id}_productions_{$startDate->toDateString()}_{$endDate->toDateString()}",
            now()->addHours(3),
            function () use ($employee, $startDate, $endDate) {
                return $employee->productions()
                    ->whereBetween('date', [$startDate, $endDate])
                    ->orderBy('date')
                    ->get()
                    ->groupBy(fn ($record) => $record->date->format('M d'));
            }
        );

        $dates = [];
        $totalHours = [];
        $efficiency = [];

        foreach ($productions as $date => $productionGroup) {
            $dates[] = $date;

            // Calculate actual SPH: total conversions / total production time (in hours)
            $totalHoursValue = $productionGroup->sum('total_time');
            $totalProductionTime = $productionGroup->sum('production_time');

            // Get goal SPH (average of all sph_goal values for the day)
            // $goalValue = round($productionGroup->avg('sph_goal'), 2);
            $totalHours[] = $totalHoursValue;

            $efficiency[] = $totalProductionTime > 0 ? round(($totalProductionTime / $totalHoursValue) * 100 , 2) : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Hours',
                    'data' => $totalHours,
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'tension' => 0.3,
                    'borderWidth' => 2,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Efficiency (%)',
                    'data' => $efficiency,
                    'borderColor' => '#8b5cf6',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                    'borderDash' => [5, 5],
                    'tension' => 0.3,
                    'borderWidth' => 2,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Total Hours',
                    ],
                ],
                'y1' => [
                    'beginAtZero' => true,
                    'position' => 'right',
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Efficiency (%)',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Date',
                    ],
                ],
            ],
        ];
    }
}
