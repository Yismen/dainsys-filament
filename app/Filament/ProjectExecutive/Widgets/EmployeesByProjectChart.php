<?php

namespace App\Filament\ProjectExecutive\Widgets;

use App\Filament\ProjectExecutive\Widgets\Concerns\InteractsWithProjectFilter;
use App\Models\Project;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class EmployeesByProjectChart extends ChartWidget
{
    use InteractsWithProjectFilter;

    protected ?string $heading = 'Employees by project';

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'bar';
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
