<?php

namespace App\Filament\OperationsDirector\Widgets;

use App\Filament\OperationsDirector\Widgets\Concerns\InteractsWithProjectAndClientFilters;
use App\Models\Project;
use Filament\Widgets\ChartWidget;

class EmployeesByProjectChart extends ChartWidget
{
    use InteractsWithProjectAndClientFilters;

    protected ?string $heading = 'Employees by project';

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'bar';
    }

    public function getData(): array
    {
        $projectIds = $this->getFilteredProjectIds();

        if (($projectIds === []) && $this->hasProjectOrClientFiltersApplied()) {
            return [
                'datasets' => [
                    [
                        'label' => 'Employees',
                        'data' => [],
                        'backgroundColor' => '#0ea5e9',
                        'borderColor' => '#0284c7',
                    ],
                ],
                'labels' => [],
            ];
        }

        $projects = Project::query()
            ->when(
                $projectIds !== [],
                fn ($query) => $query->whereIn('id', $projectIds),
            )
            ->withCount([
                'employees as active_employees_count' => function ($query): void {
                    $query->active();
                },
            ])
            ->orderByDesc('active_employees_count')
            ->orderBy('name')
            ->get(['id', 'name']);

        return [
            'datasets' => [
                [
                    'label' => 'Employees',
                    'data' => $projects->pluck('active_employees_count')->toArray(),
                    'backgroundColor' => '#0ea5e9',
                    'borderColor' => '#0284c7',
                ],
            ],
            'labels' => $projects->pluck('name')->toArray(),
        ];
    }
}
