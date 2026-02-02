<?php

use App\Filament\Admin\Widgets\ActivityLogWidget;
use App\Filament\Admin\Widgets\AdminOverviewStats;
use App\Filament\Admin\Widgets\FailedJobsStatsWidget;
use App\Filament\Admin\Widgets\MailQueueWidget;
use App\Filament\Admin\Widgets\PermissionsOverviewWidget;
use App\Filament\Admin\Widgets\RecentUsersWidget;
use App\Filament\Admin\Widgets\SchedulerStatusWidget;

test('admin overview stats widget can be instantiated', function () {
    $widget = new AdminOverviewStats;
    expect($widget)->toBeInstanceOf(AdminOverviewStats::class);
});

test('failed jobs stats widget can be instantiated', function () {
    $widget = new FailedJobsStatsWidget;
    expect($widget)->toBeInstanceOf(FailedJobsStatsWidget::class);
});

test('permissions overview widget can be instantiated', function () {
    $widget = new PermissionsOverviewWidget;
    expect($widget)->toBeInstanceOf(PermissionsOverviewWidget::class);
});

test('mail queue widget can be instantiated', function () {
    $widget = new MailQueueWidget;
    expect($widget)->toBeInstanceOf(MailQueueWidget::class);
});

test('scheduler status widget can be instantiated', function () {
    $widget = new SchedulerStatusWidget;
    expect($widget)->toBeInstanceOf(SchedulerStatusWidget::class);
});

test('recent users widget can be instantiated', function () {
    $widget = new RecentUsersWidget;
    expect($widget)->toBeInstanceOf(RecentUsersWidget::class);
});

test('activity log widget can be instantiated', function () {
    $widget = new ActivityLogWidget;
    expect($widget)->toBeInstanceOf(ActivityLogWidget::class);
});
