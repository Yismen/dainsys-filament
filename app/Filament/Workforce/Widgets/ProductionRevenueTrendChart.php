<?php

namespace App\Filament\Workforce\Widgets;

use App\Models\Production;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ProductionRevenueTrendChart extends ChartWidget
{
    protected ?string $heading = 'Production revenue trend (last 14 days)';

    protected int|string|array $columnSpan = 1;

    protected ?string $maxHeight = '300px';

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $since = Carbon::today()->subDays(14);

        $byDate = Production::query()
            ->whereDate('date', '>=', $since)
            ->orderBy('date')
            ->get(['date', 'revenue', 'billable_time'])
            ->groupBy(fn ($row) => $row->date?->format('Y-m-d'));

        $labels = $byDate->keys()->map(fn ($date) => Carbon::parse($date)->format('M d'))->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $byDate->map(fn ($row) => round(($row->sum('revenue') ?? 0), 2))->values(),
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.15)',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Billable time',
                    'data' => $byDate->map(fn ($row) => $row->sum('billable_time') ?? 0)->values(),
                    'borderColor' => '#0ea5e9',
                    'backgroundColor' => 'rgba(14, 165, 233, 0.15)',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array|RawJs|null
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
                        'text' => 'Revenue ($) / Minutes',
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
