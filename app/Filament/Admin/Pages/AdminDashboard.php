<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\ActivityLogWidget;
use App\Filament\Admin\Widgets\AdminOverviewStats;
use App\Filament\Admin\Widgets\FailedJobsStatsWidget;
use App\Filament\Admin\Widgets\MailQueueWidget;
use App\Filament\Admin\Widgets\PermissionsOverviewWidget;
use App\Filament\Admin\Widgets\RecentUsersWidget;
use App\Filament\Admin\Widgets\SchedulerStatusWidget;
use Filament\Pages\Dashboard;

class AdminDashboard extends Dashboard
{
    protected static ?string $title = 'Dashboard';

    protected static bool $shouldRegisterNavigation = false;

    /**
     * @return array<class-string<\Filament\Widgets\Widget>|string>
     */
    public function getWidgets(): array
    {
        return [
            AdminOverviewStats::class,
            FailedJobsStatsWidget::class,
            PermissionsOverviewWidget::class,
            MailQueueWidget::class,
            SchedulerStatusWidget::class,
            RecentUsersWidget::class,
            ActivityLogWidget::class,
        ];
    }

    public function getColumns(): array|int
    {
        return [
            'md' => 2,
            'lg' => 2,
        ];
    }
}
