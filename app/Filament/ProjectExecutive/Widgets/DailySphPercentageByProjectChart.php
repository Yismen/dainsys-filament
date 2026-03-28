<?php

namespace App\Filament\ProjectExecutive\Widgets;

use App\Models\Production;
use App\Models\Project;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DailySphPercentageByProjectChart extends ChartWidget
{
    protected ?string $heading = 'Daily SPH % by project (last 10 days)';

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'line';
    }

    public function getData(): array
    {
        $managerId = Auth::id();

        $projects = Project::query()
            ->where('manager_id', $managerId)
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
            ->whereHas('employee.project', function ($query) use ($managerId): void {
                $query->where('manager_id', $managerId);
            })
            ->with('employee:id,project_id')
            ->get(['date', 'employee_id', 'conversions', 'production_time', 'conversions_goal']);

        $totalsByProjectAndDate = [];

        foreach ($productions as $production) {
            $projectId = $production->employee?->project_id;
            $dateKey = filled($production->date)
                ? Carbon::parse($production->date)->toDateString()
                : null;

            if (! $projectId || ! $dateKey) {
                continue;
            }

            $totalsByProjectAndDate[$projectId][$dateKey]['conversions'] =
                ($totalsByProjectAndDate[$projectId][$dateKey]['conversions'] ?? 0) + (float) $production->conversions;

            $totalsByProjectAndDate[$projectId][$dateKey]['production_time'] =
                ($totalsByProjectAndDate[$projectId][$dateKey]['production_time'] ?? 0) + (float) $production->production_time;

            $totalsByProjectAndDate[$projectId][$dateKey]['conversions_goal'] =
                ($totalsByProjectAndDate[$projectId][$dateKey]['conversions_goal'] ?? 0) + (float) $production->conversions_goal;
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
            ->map(function (Project $project, int $index) use ($totalsByProjectAndDate, $dateKeys, $palette): array {
                $color = $palette[$index % count($palette)];

                return [
                    'label' => $project->name,
                    'data' => collect($dateKeys)->map(function (string $dateKey) use ($totalsByProjectAndDate, $project): float {
                        $conversions = (float) ($totalsByProjectAndDate[$project->id][$dateKey]['conversions'] ?? 0);
                        $productionTime = (float) ($totalsByProjectAndDate[$project->id][$dateKey]['production_time'] ?? 0);
                        $conversionsGoal = (float) ($totalsByProjectAndDate[$project->id][$dateKey]['conversions_goal'] ?? 0);

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
