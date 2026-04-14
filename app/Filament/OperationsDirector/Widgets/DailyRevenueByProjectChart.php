<?php

namespace App\Filament\OperationsDirector\Widgets;

use App\Filament\OperationsDirector\Widgets\Concerns\InteractsWithProjectAndClientFilters;
use App\Models\Production;
use App\Models\Project;
use App\Traits\Filament\HasColors;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class DailyRevenueByProjectChart extends ChartWidget
{
    use InteractsWithProjectAndClientFilters;
    use HasColors;

    protected ?string $heading = 'Daily revenue by project (last 10 days)';

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'line';
    }

    public function getData(): array
    {
        $projectIds = $this->getFilteredProjectIds();

        if (($projectIds === []) && $this->hasProjectOrClientFiltersApplied()) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $projects = Project::query()
            ->when(
                $projectIds !== [],
                fn ($query) => $query->whereIn('id', $projectIds),
            )
            ->orderBy('name')
            ->get(['id', 'name']);

        if ($projects->isEmpty()) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $startDate = Carbon::today()->subDays(9)->startOfDay();
        $endDate = Carbon::today()->endOfDay();

        $dates = collect(range(0, 9))
            ->map(fn (int $dayOffset): Carbon => $startDate->copy()->addDays($dayOffset));

        $labels = $dates->map(fn (Carbon $date): string => $date->format('M d'))->toArray();
        $dateKeys = $dates->map(fn (Carbon $date): string => $date->toDateString())->toArray();

        $productions = Production::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->when(
                $projectIds !== [],
                fn ($query) => $query->whereHas('employee', fn ($employeeQuery) => $employeeQuery->whereIn('project_id', $projectIds)),
            )
            ->when(
                ($projectIds === []) && $this->hasProjectOrClientFiltersApplied(),
                fn ($query) => $query->whereRaw('1 = 0'),
            )
            ->with('employee:id,project_id')
            ->get(['date', 'employee_id', 'revenue']);

        $aggregatedRevenue = [];

        foreach ($productions as $production) {
            $projectId = $production->employee?->project_id;
            $dateKey = filled($production->date)
                ? Carbon::parse($production->date)->toDateString()
                : null;

            if (! $projectId || ! $dateKey) {
                continue;
            }

            $aggregatedRevenue[$projectId][$dateKey] = ($aggregatedRevenue[$projectId][$dateKey] ?? 0) + (float) $production->revenue;
        }

        $palette = [
            '#0ea5e9',
            '#22c55e',
            '#f97316',
            '#8b5cf6',
            '#ef4444',
            '#14b8a6',
        ];

        $datasets = $projects
            ->values()
            ->map(function (Project $project, int $index) use ($aggregatedRevenue, $dateKeys, $palette): array {
                $color = $palette[$index % count($palette)];

                return $this->makeLineChartDataset(
                    $project->name,
                    collect($dateKeys)
                        ->map(fn (string $dateKey): float => round((float) ($aggregatedRevenue[$project->id][$dateKey] ?? 0), 2))
                        ->toArray(),
                    $color,
                    [
                        'tension' => 0.3,
                    ],
                );
            })
            ->toArray();

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }
}
