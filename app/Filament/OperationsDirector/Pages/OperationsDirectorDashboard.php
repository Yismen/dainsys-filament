<?php

namespace App\Filament\OperationsDirector\Pages;

use App\Filament\OperationsDirector\Widgets\AbsencesByEmployeeTable;
use App\Filament\OperationsDirector\Widgets\EmployeesByProjectChart;
use App\Filament\OperationsDirector\Widgets\OperationsDirectorQAStatsWidget;
use App\Filament\OperationsDirector\Widgets\OperationsDirectorStatsOverview;
use App\Filament\OperationsDirector\Widgets\UpcomingBirthdaysTable;
use App\Filament\OperationsDirector\Widgets\WeeklyEfficiencyByProjectChart;
use App\Filament\OperationsDirector\Widgets\WeeklyRevenueByProjectChart;
use App\Filament\OperationsDirector\Widgets\WeeklySphPercentageByProjectChart;
use App\Models\Client;
use App\Models\Project;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OperationsDirectorDashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static string $routePath = '/';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = -2;

    public function getWidgets(): array
    {
        return [
            OperationsDirectorStatsOverview::class,
            OperationsDirectorQAStatsWidget::class,
            EmployeesByProjectChart::class,
            WeeklyRevenueByProjectChart::class,
            WeeklyEfficiencyByProjectChart::class,
            WeeklySphPercentageByProjectChart::class,
            AbsencesByEmployeeTable::class,
            UpcomingBirthdaysTable::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 2;
    }

    public function persistsFiltersInSession(): bool
    {
        return false;
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'md' => 1,
                'xl' => 1,
                '2xl' => 1,
            ])
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        Select::make('project')
                            ->label(__('Projects'))
                            ->searchable()
                            ->multiple()
                            ->live()
                            ->options(fn (): array => ModelListService::make(
                                Project::query()->orderBy('name')
                            )),
                        Select::make('client')
                            ->label(__('Clients'))
                            ->searchable()
                            ->multiple()
                            ->live()
                            ->options(fn (): array => ModelListService::make(
                                Client::query()->orderBy('name')
                            )),
                    ]),
            ]);
    }
}
