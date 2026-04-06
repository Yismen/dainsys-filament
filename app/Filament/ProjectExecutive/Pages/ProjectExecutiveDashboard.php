<?php

namespace App\Filament\ProjectExecutive\Pages;

use App\Filament\ProjectExecutive\Widgets\AbsencesByEmployeeTable;
use App\Filament\ProjectExecutive\Widgets\DailyEfficiencyByProjectChart;
use App\Filament\ProjectExecutive\Widgets\DailyRevenueByProjectChart;
use App\Filament\ProjectExecutive\Widgets\DailySphPercentageByProjectChart;
use App\Filament\ProjectExecutive\Widgets\EmployeesByProjectChart;
use App\Filament\ProjectExecutive\Widgets\MonthlyRevenueByProjectChart;
use App\Filament\ProjectExecutive\Widgets\ProjectExecutiveQAStatsWidget;
use App\Filament\ProjectExecutive\Widgets\ProjectExecutiveStatsOverview;
use App\Filament\ProjectExecutive\Widgets\UpcomingBirthdaysTable;
use App\Models\Project;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ProjectExecutiveDashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static string $routePath = '/';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = -2;

    public function getWidgets(): array
    {
        return [
            ProjectExecutiveStatsOverview::class,
            ProjectExecutiveQAStatsWidget::class,
            EmployeesByProjectChart::class,
            MonthlyRevenueByProjectChart::class,
            DailyRevenueByProjectChart::class,
            DailyEfficiencyByProjectChart::class,
            DailySphPercentageByProjectChart::class,
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
                    ->columns(1)
                    ->schema([
                        Select::make('project')
                            ->label(__('Projects'))
                            ->searchable()
                            ->multiple()
                            ->live()
                            ->options(fn (): array => ModelListService::make(
                                Project::query()
                                    ->where('manager_id', Auth::id())
                                    ->orderBy('name')
                            )),
                    ]),
            ]);
    }
}
