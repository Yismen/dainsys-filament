<?php

namespace App\Filament\Supervisor\Pages;

use App\Filament\Supervisor\Widgets\SupervisorQAStatsWidget;
use App\Filament\Supervisor\Widgets\SupervisorStatsOverview;
use App\Filament\Supervisor\Widgets\UpcomingBirthdaysTable;
use Filament\Pages\Dashboard as BaseDashboard;

class SupervisorDashboard extends BaseDashboard
{
    protected static string $routePath = '/';

    public function getWidgets(): array
    {
        return [
            SupervisorStatsOverview::class,
            SupervisorQAStatsWidget::class,
            UpcomingBirthdaysTable::class,
        ];
    }
}
