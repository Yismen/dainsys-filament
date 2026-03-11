<?php

namespace App\Filament\HumanResource\Widgets;

use App\Models\Employee;
use App\Models\Hire;
use App\Models\Termination;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class HiresVsTerminationsChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Hires vs Terminations';

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

            return Hire::query()
                ->whereYear('date', $year)
                ->whereMonth('date', $m)
                ->when(! empty($this->filters['site']), fn ($q) => $q->whereIn('site_id', $this->filters['site']))
                ->when(! empty($this->filters['project']), fn ($q) => $q->whereIn('project_id', $this->filters['project']))
                ->when(! empty($this->filters['supervisor']), fn ($q) => $q->whereIn('supervisor_id', $this->filters['supervisor']))
                ->count();
        });

        $terminations = $months->map(function ($month) use ($query) {
            [$year, $m] = explode('-', $month);

            return Termination::query()
                 ->whereYear('date', $year)
                ->whereMonth('date', $m)
                ->when(! empty($this->filters['site']), fn ($q) => $q->whereHas('employee', fn ($q2) => $q2->whereIn('site_id', $this->filters['site'])))
                ->when(! empty($this->filters['project']), fn ($q) => $q->whereHas('employee', fn ($q2) => $q2->whereIn('project_id', $this->filters['project'])))
                ->when(! empty($this->filters['supervisor']), fn ($q) => $q->whereHas('employee', fn ($q2) => $q2->whereIn('supervisor_id', $this->filters['supervisor'])))
                 ->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Hires',
                    'data' => $hires,
                    'borderColor' => 'rgba(34,197,94,0.7)',
                    'backgroundColor' => 'rgba(34,197,94,0.2)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Terminations',
                    'data' => $terminations,
                    'borderColor' => 'rgba(239,68,68,0.7)',
                    'backgroundColor' => 'rgba(239,68,68,0.2)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $months->map(fn ($month) => Carbon::createFromFormat('Y-m', $month)->format('M Y'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
