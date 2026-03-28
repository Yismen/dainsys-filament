<?php

namespace App\Filament\OperationsDirector\Pages;

use App\Filament\OperationsDirector\Widgets\AbsencesByEmployeeTable;
use App\Filament\OperationsDirector\Widgets\DailyEfficiencyByProjectChart;
use App\Filament\OperationsDirector\Widgets\DailyRevenueByProjectChart;
use App\Filament\OperationsDirector\Widgets\DailySphPercentageByProjectChart;
use App\Filament\OperationsDirector\Widgets\EmployeesByProjectChart;
use App\Filament\OperationsDirector\Widgets\OperationsDirectorQAStatsWidget;
use App\Filament\OperationsDirector\Widgets\OperationsDirectorStatsOverview;
use App\Filament\OperationsDirector\Widgets\UpcomingBirthdaysTable;
use Filament\Pages\Dashboard as BaseDashboard;

class OperationsDirectorDashboard extends BaseDashboard
{
    protected static string $routePath = '/';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = -2;

    public function getWidgets(): array
    {
        return [
            OperationsDirectorStatsOverview::class,
            OperationsDirectorQAStatsWidget::class,
            EmployeesByProjectChart::class,
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
}
