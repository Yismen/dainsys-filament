<?php

namespace App\Filament\Workforce\Widgets;

use App\Models\Downtime;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class DowntimeByReasonChart extends ChartWidget
{
    protected ?string $heading = 'Downtime by reason (last 30 days)';

    protected int|string|array $columnSpan = 1;

    protected ?string $maxHeight = '300px';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $since = Carbon::today()->subDays(30);

        $byReason = Downtime::query()
            ->selectRaw('downtime_reason_id, SUM(total_time) as total_minutes')
            ->whereDate('date', '>=', $since)
            ->with('downtimeReason')
            ->groupBy('downtime_reason_id')
            ->orderByDesc('total_minutes')
            ->get()
            ->filter(fn ($row) => $row->downtimeReason)
            ->values();

        return [
            'datasets' => [
                [
                    'label' => 'Minutes',
                    'data' => $byReason->pluck('total_minutes'),
                    'backgroundColor' => '#ef4444',
                ],
            ],
            'labels' => $byReason->map(fn ($row) => $row->downtimeReason->name),
        ];
    }

    protected function getOptions(): array|RawJs|null
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Minutes',
                    ],
                ],
            ],
        ];
    }
}
