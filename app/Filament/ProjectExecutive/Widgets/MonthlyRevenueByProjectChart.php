<?php

namespace App\Filament\ProjectExecutive\Widgets;

use App\Filament\ProjectExecutive\Widgets\Concerns\InteractsWithProjectFilter;
use App\Models\Production;
use App\Models\Project;
use App\Traits\Filament\HasColors;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class MonthlyRevenueByProjectChart extends ChartWidget
{
    use InteractsWithProjectFilter;
    use HasColors;

    protected ?string $heading = 'Monthly revenue by project (last 6 months)';

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

        $startMonth = Carbon::today()->startOfMonth()->subMonths(5);
        $endMonth = Carbon::today()->endOfMonth();

        $months = collect(range(0, 5))
            ->map(fn (int $monthOffset): Carbon => $startMonth->copy()->addMonths($monthOffset));

        $labels = $months->map(fn (Carbon $month): string => $month->format('M Y'))->toArray();
        $monthKeys = $months->map(fn (Carbon $month): string => $month->format('Y-m'))->toArray();

        $productions = Production::query()
            ->whereBetween('date', [$startMonth, $endMonth])
            ->whereHas('employee.project', function ($query) use ($managerId, $selectedProjectIds): void {
                $query->where('manager_id', $managerId)
                    ->when(
                        $selectedProjectIds !== [],
                        fn ($builder) => $builder->whereIn('id', $selectedProjectIds),
                    );
            })
            ->with('employee:id,project_id')
            ->get(['date', 'employee_id', 'revenue']);

        $aggregatedRevenue = [];

        foreach ($productions as $production) {
            $projectId = $production->employee?->project_id;
            $monthKey = filled($production->date)
                ? Carbon::parse($production->date)->format('Y-m')
                : null;

            if (! $projectId || ! $monthKey) {
                continue;
            }

            $aggregatedRevenue[$projectId][$monthKey] = ($aggregatedRevenue[$projectId][$monthKey] ?? 0) + (float) $production->revenue;
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
            ->map(function (Project $project, int $index) use ($aggregatedRevenue, $monthKeys, $palette): array {
                $color = $palette[$index % count($palette)];

                return $this->makeLineChartDataset(
                    $project->name,
                    collect($monthKeys)
                        ->map(fn (string $monthKey): float => round((float) ($aggregatedRevenue[$project->id][$monthKey] ?? 0), 2))
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
