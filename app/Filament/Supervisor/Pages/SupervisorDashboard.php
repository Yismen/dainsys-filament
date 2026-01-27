<?php

namespace App\Filament\Supervisor\Pages;

use App\Filament\Supervisor\Widgets\SupervisorStatsOverview;
use App\Filament\Supervisor\Widgets\UpcomingBirthdaysTable;
use Filament\Pages\Dashboard as BaseDashboard;

class SupervisorDashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            SupervisorStatsOverview::class,
            UpcomingBirthdaysTable::class,
        ];
    }
}
