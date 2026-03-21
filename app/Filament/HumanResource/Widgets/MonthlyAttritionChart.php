<?php

namespace App\Filament\HumanResource\Widgets;

use App\Models\Employee;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class MonthlyAttritionChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Monthly Attrition';

    protected ?string $pollingInterval = null;

    protected function getData(): array
    {
        $query = Employee::query();

        if (! empty($this->filters['site'])) {
            $query->whereIn('site_id', $this->filters['site']);
        }
        if (! empty($this->filters['project'])) {
            $query->whereIn('project_id', $this->filters['project']);
        }
        if (! empty($this->filters['supervisor'])) {
            $query->whereIn('supervisor_id', $this->filters['supervisor']);
        }

        $months = collect(range(0, 5))
            ->map(fn ($i) => Carbon::now()->subMonths($i)->format('Y-m'))
            ->reverse()
            ->values();

        $data = $months->map(function ($month) use ($query) {
            [$year, $m] = explode('-', $month);
            // Terminations in this month
            $terminations = $query->clone()
                ->whereYear('terminated_at', $year)
                ->whereMonth('terminated_at', $m)
                ->count();

            // Headcount at the start of the month: employees hired before or in this month and not terminated before this month
            $startOfMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $endOfMonth = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
            $headcount = $query->clone()
                ->where(function ($q) use ($endOfMonth): void {
                    $q->whereNull('terminated_at')->orWhere('terminated_at', '>', $endOfMonth);
                })
                ->where('hired_at', '<=', $endOfMonth)
                ->count();

            // Avoid division by zero
            $rate = $headcount > 0 ? round(($terminations / $headcount) * 100, 2) : 0;

            return [
                'head_count' => $headcount,
                'terminations' => $terminations,
                'rate' => $rate,
                'month' => $month,
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Attrition Rate (%)',
                    'data' => $data->pluck('rate')->toArray(),
                    'borderColor' => 'rgba(165,0,165,0.7)', // purple
                    'backgroundColor' => 'rgba(165,0,165,0.03)',
                    'borderDash' => [5, 5],
                    'fill' => false,
                    'tension' => 0.3,
                    'yAxisID' => 'y1',
                ],
                [
                    'label' => 'Headcount',
                    'data' => $data->pluck('head_count')->toArray(),
                    'borderColor' => 'rgba(34,197,94,0.7)',
                    'backgroundColor' => 'rgba(34,197,94,0.03)',
                    'fill' => true,
                    'tension' => 0.3,
                    'yAxisID' => 'y',
                ],
            ],
            'labels' => $months->map(fn ($month) => Carbon::createFromFormat('Y-m', $month)->format('M Y'))->toArray(),
        ];
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
                        'text' => 'Headcount',
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
                        'text' => 'Attrition Rate (%)',
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

    protected function getType(): string
    {
        return 'line';
    }
}
