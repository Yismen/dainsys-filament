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

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $since = Carbon::today()->subDays(14);

        $byDate = Production::query()
            ->selectRaw('date, SUM(revenue) as revenue_cents, SUM(billable_time) as billable_minutes')
            ->whereDate('date', '>=', $since)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $byDate->map(fn ($row) => $row->date?->format('M d'));

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $byDate->map(fn ($row) => round(($row->revenue_cents ?? 0) / 100, 2)),
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.15)',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Billable minutes',
                    'data' => $byDate->map(fn ($row) => $row->billable_minutes ?? 0),
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
