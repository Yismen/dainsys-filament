<?php

namespace App\Filament\HumanResource\Widgets;

use App\Models\Employee;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class HiresVsTerminationsChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Hires vs Terminations';

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

        $hires = $months->map(function ($month) use ($query) {
            [$year, $m] = explode('-', $month);

            return $query->clone()
                ->whereYear('hired_at', $year)
                ->whereMonth('hired_at', $m)
                ->count();
        });

        $terminations = $months->map(function ($month) use ($query) {
            [$year, $m] = explode('-', $month);

            return $query->clone()
                ->whereYear('terminated_at', $year)
                ->whereMonth('terminated_at', $m)
                ->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Hires',
                    'data' => $hires,
                    'borderColor' => 'rgba(34,197,94,0.7)',
                    'backgroundColor' => 'rgba(34,197,94,0.2)',
                    'fill' => false,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Terminations',
                    'data' => $terminations,
                    'borderColor' => 'rgba(239,68,68,0.7)',
                    'backgroundColor' => 'rgba(239,68,68,0.2)',
                    'fill' => false,
                    'borderDash' => [5, 5],
                    'tension' => 0.3,
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
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
