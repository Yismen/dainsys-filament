<?php

namespace App\Filament\Employee\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ProductionSalesWidget extends ChartWidget
{
    protected ?string $heading = 'Production Sales (Last 14 Days)';

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
        $sales = [];

        foreach ($productions as $date => $production) {
            $dates[] = $date;
            $sales[] = $production->sum('conversions');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Daily Sales',
                    'data' => $sales,
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#059669',
                    'borderWidth' => 1,
                    'tension' => 0.3,
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
