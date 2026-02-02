<?php

namespace App\Filament\Employee\Pages;

use App\Filament\Employee\Widgets\DowntimeBreakdownWidget;
use App\Filament\Employee\Widgets\EfficiencyTrendWidget;
use App\Filament\Employee\Widgets\EmployeeStatsWidget;
use App\Filament\Employee\Widgets\HoursTrendWidget;
use App\Filament\Employee\Widgets\ProductionSalesWidget;
use App\Filament\Employee\Widgets\SPHTrendWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class EmployeeDashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = -2;

    public function getWidgets(): array
    {
        return [
            EmployeeStatsWidget::class,
            SPHTrendWidget::class,
            ProductionSalesWidget::class,
            EfficiencyTrendWidget::class,
            HoursTrendWidget::class,
            DowntimeBreakdownWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 2;
    }
}
