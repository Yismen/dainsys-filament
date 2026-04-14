<?php

namespace App\Filament\Employee\Widgets;

use App\Traits\Filament\HasColors;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class EfficiencyTrendWidget extends ChartWidget
{
    use HasColors;

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

        $dailyEfficiency = Cache::remember(
            "employee_{$employee->id}_efficiency_trend_{$startDate->toDateString()}_{$endDate->toDateString()}",
            now()->addHours(3),
            function () use ($employee, $startDate, $endDate): array {
                return $employee->productions()
                    ->whereBetween('date', [$startDate, $endDate])
                    ->orderBy('date')
                    ->get()
                    ->groupBy(fn ($record) => $record->date->format('M d'))
                    ->map(function ($productionGroup, string $date): array {
                        $totalHoursValue = (float) $productionGroup->sum('total_time');
                        $totalProductionTime = (float) $productionGroup->sum('production_time');

                        return [
                            'date' => $date,
                            'total_hours' => $totalHoursValue,
                            'efficiency' => $totalHoursValue > 0 ? round(($totalProductionTime / $totalHoursValue) * 100, 2) : 0,
                        ];
                    })
                    ->values()
                    ->all();
            }
        );

        $dates = [];
        $totalHours = [];
        $efficiency = [];

        foreach ($dailyEfficiency as $dailyRecord) {
            $dates[] = $dailyRecord['date'];
            $totalHours[] = $dailyRecord['total_hours'];
            $efficiency[] = $dailyRecord['efficiency'];
        }

        return [
            'datasets' => [
                $this->makeLineChartDataset('Total Hours', $totalHours, '#22c55e', [
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'tension' => 0.3,
                    'borderWidth' => 2,
                    'yAxisID' => 'y',
                ]),
                $this->makeLineChartDataset('Efficiency (%)', $efficiency, '#8b5cf6', [
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                    'borderDash' => [5, 5],
                    'tension' => 0.3,
                    'borderWidth' => 2,
                    'yAxisID' => 'y1',
                ]),
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
