<?php

namespace App\Filament\Workforce\Pages;

use App\Filament\Workforce\Widgets\DowntimeByReasonChart;
use App\Filament\Workforce\Widgets\PendingDowntimesTable;
use App\Filament\Workforce\Widgets\ProductionRevenueTrendChart;
use App\Filament\Workforce\Widgets\RecentDowntimesTable;
use App\Filament\Workforce\Widgets\WorkforceStatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            WorkforceStatsOverview::class,
            ProductionRevenueTrendChart::class,
            DowntimeByReasonChart::class,
            PendingDowntimesTable::class,
            RecentDowntimesTable::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 2;
    }
}
