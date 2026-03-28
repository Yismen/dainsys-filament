<?php

namespace App\Filament\ProjectExecutive\Pages;

use App\Filament\ProjectExecutive\Widgets\AbsencesByEmployeeTable;
use App\Filament\ProjectExecutive\Widgets\DailyEfficiencyByProjectChart;
use App\Filament\ProjectExecutive\Widgets\DailyRevenueByProjectChart;
use App\Filament\ProjectExecutive\Widgets\DailySphPercentageByProjectChart;
use App\Filament\ProjectExecutive\Widgets\EmployeesByProjectChart;
use App\Filament\ProjectExecutive\Widgets\ProjectExecutiveStatsOverview;
use App\Filament\ProjectExecutive\Widgets\UpcomingBirthdaysTable;
use Filament\Pages\Dashboard as BaseDashboard;

class ProjectExecutiveDashboard extends BaseDashboard
{
    protected static string $routePath = '/';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = -2;

    public function getWidgets(): array
    {
        return [
            ProjectExecutiveStatsOverview::class,
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
