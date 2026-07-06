<?php

namespace App\Filament\ProjectExecutive\Widgets;

use App\Filament\ProjectExecutive\Widgets\Concerns\InteractsWithProjectFilter;
use App\Models\Production;
use App\Models\Project;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class WeeklyEfficiencyByProjectChart extends ChartWidget
{
    use InteractsWithProjectFilter;

    protected ?string $heading = 'Weekly efficiency by project (last 10 weeks)';

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'line';
    }

    public function getData(): array
    {
        $managerId = Auth::id();
        $selectedProjectIds = $this->getSelectedProjectIdsFromPageFilters();

        $projects = Project::query()
            ->where('manager_id', $managerId)
            ->when(
                $selectedProjectIds !== [],
                fn ($query) => $query->whereIn('id', $selectedProjectIds),
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
            ->whereHas('employee.project', function ($query) use ($managerId, $selectedProjectIds): void {
                $query->where('manager_id', $managerId)
                    ->when(
                        $selectedProjectIds !== [],
                        fn ($builder) => $builder->whereIn('id', $selectedProjectIds),
                    );
            })
            ->with('employee:id,project_id')
            ->get(['date', 'employee_id', 'production_time', 'total_time']);

        $totalsByProjectAndWeek = [];

        foreach ($productions as $production) {
            $projectId = $production->employee?->project_id;
            $weekKey = filled($production->date)
                ? Carbon::parse($production->date)->startOfWeek()->toDateString()
                : null;

            if (! $projectId || ! $weekKey) {
                continue;
            }

            $totalsByProjectAndWeek[$projectId][$weekKey]['production_time'] =
                ($totalsByProjectAndWeek[$projectId][$weekKey]['production_time'] ?? 0) + (float) $production->production_time;

            $totalsByProjectAndWeek[$projectId][$weekKey]['total_time'] =
                ($totalsByProjectAndWeek[$projectId][$weekKey]['total_time'] ?? 0) + (float) $production->total_time;
        }

        $palette = [
            '#22c55e',
            '#0ea5e9',
            '#f97316',
            '#8b5cf6',
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
                        $productionTime = (float) ($totalsByProjectAndWeek[$project->id][$weekKey]['production_time'] ?? 0);
                        $totalTime = (float) ($totalsByProjectAndWeek[$project->id][$weekKey]['total_time'] ?? 0);

                        if ($totalTime <= 0) {
                            return 0;
                        }

                        return round(($productionTime / $totalTime) * 100, 2);
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
