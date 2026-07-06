<?php

namespace App\Filament\OperationsDirector\Widgets;

use App\Filament\OperationsDirector\Widgets\Concerns\InteractsWithProjectAndClientFilters;
use App\Models\Production;
use App\Models\Project;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class WeeklySphPercentageByProjectChart extends ChartWidget
{
    use InteractsWithProjectAndClientFilters;

    protected ?string $heading = 'Weekly SPH % by project (last 10 weeks)';

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

        $startDate = Carbon::today()->subWeeks(9)->startOfWeek();
        $endDate = Carbon::today()->endOfWeek();

        $weeks = collect(range(0, 9))
            ->map(fn (int $weekOffset): Carbon => $startDate->copy()->addWeeks($weekOffset));

        $labels = $weeks->map(fn (Carbon $weekStart): string => $weekStart->format('M d').' - '.$weekStart->copy()->endOfWeek()->format('M d'))->toArray();
        $weekKeys = $weeks->map(fn (Carbon $weekStart): string => $weekStart->toDateString())->toArray();

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
            ->get(['date', 'employee_id', 'conversions', 'production_time', 'conversions_goal']);

        $totalsByProjectAndWeek = [];

        foreach ($productions as $production) {
            $projectId = $production->employee?->project_id;
            $weekKey = filled($production->date)
                ? Carbon::parse($production->date)->startOfWeek()->toDateString()
                : null;

            if (! $projectId || ! $weekKey) {
                continue;
            }

            $totalsByProjectAndWeek[$projectId][$weekKey]['conversions'] =
                ($totalsByProjectAndWeek[$projectId][$weekKey]['conversions'] ?? 0) + (float) $production->conversions;

            $totalsByProjectAndWeek[$projectId][$weekKey]['production_time'] =
                ($totalsByProjectAndWeek[$projectId][$weekKey]['production_time'] ?? 0) + (float) $production->production_time;

            $totalsByProjectAndWeek[$projectId][$weekKey]['conversions_goal'] =
                ($totalsByProjectAndWeek[$projectId][$weekKey]['conversions_goal'] ?? 0) + (float) $production->conversions_goal;
        }

        $palette = [
            '#8b5cf6',
            '#0ea5e9',
            '#22c55e',
            '#f97316',
            '#ef4444',
            '#14b8a6',
        ];

        $datasets = $projects
            ->values()
            ->map(function (Project $project, int $index) use ($totalsByProjectAndWeek, $weekKeys, $palette): array {
                $color = $palette[$index % count($palette)];

                return [
                    'label' => $project->name,
                    'data' => collect($weekKeys)->map(function (string $weekKey) use ($totalsByProjectAndWeek, $project): float {
                        $conversions = (float) ($totalsByProjectAndWeek[$project->id][$weekKey]['conversions'] ?? 0);
                        $productionTime = (float) ($totalsByProjectAndWeek[$project->id][$weekKey]['production_time'] ?? 0);
                        $conversionsGoal = (float) ($totalsByProjectAndWeek[$project->id][$weekKey]['conversions_goal'] ?? 0);

                        if ($productionTime <= 0 || $conversionsGoal <= 0) {
                            return 0;
                        }

                        $actualSph = $conversions / $productionTime;
                        $goalSph = $conversionsGoal / $productionTime;

                        if ($goalSph <= 0) {
                            return 0;
                        }

                        return round(($actualSph / $goalSph) * 100, 2);
                    })->toArray(),
                    'borderColor' => $color,
                    'backgroundColor' => $color,
                    'tension' => 0.3,
                ];
            })
            ->toArray();

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }
}
