<?php

namespace App\Filament\Employee\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DowntimeBreakdownWidget extends ChartWidget
{
    protected ?string $heading = 'Downtime Breakdown (Last 30 Days)';

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

        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        $downtimeBreakdown = Cache::remember(
            "employee_{$employee->id}_downtime_breakdown_{$startDate->toDateString()}_{$endDate->toDateString()}",
            now()->addHours(3),
            function () use ($employee, $startDate, $endDate): array {
                return $employee->downtimes()
                    ->whereBetween('date', [$startDate, $endDate])
                    ->orderBy('date')
                    ->get()
                    ->groupBy('reason')
                    ->map(fn ($items, ?string $reason): array => [
                        'reason' => $reason ?? 'Unknown',
                        'total_time' => (float) $items->sum('total_time'),
                    ])
                    ->values()
                    ->all();
            }
        );

        $labels = [];
        $data = [];
        $colors = ['#ef4444', '#f97316', '#eab308', '#84cc16', '#22c55e', '#10b981', '#14b8a6', '#06b6d4', '#0ea5e9', '#3b82f6'];

        $colorIndex = 0;
        foreach ($downtimeBreakdown as $downtime) {
            $labels[] = $downtime['reason'];
            $data[] = [
                'value' => $downtime['total_time'],
                'color' => $colors[$colorIndex % count($colors)],
            ];
            $colorIndex++;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Downtime (hours)',
                    'data' => array_column($data, 'value'),
                    'backgroundColor' => array_column($data, 'color'),
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
