<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Pages\Dashboard;
use App\Filament\Traits\WorkforceSupportMenu;

class WorkforceDashboard extends Dashboard
{
    use WorkforceSupportMenu;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // protected static string $view = 'filament.app.pages.workforce-dashboard';
    protected static string $routePath = 'workforce';
    protected static ?string $title = 'Dashboard';
    protected static ?int $navigationSort = -1;

    public function getWidgets(): array
    {
        return [
            // \App\Filament\App\Widgets\HumanResource\EmployeesStats::class,
            // \App\Filament\App\Widgets\HumanResource\HeadCountBySite::class,
            // \App\Filament\App\Widgets\HumanResource\HeadCountByDepartment::class,
            // \App\Filament\App\Widgets\HumanResource\HeadCountByProject::class,
            // \App\Filament\App\Widgets\HumanResource\HeadCountByPosition::class,
            // \App\Filament\App\Widgets\HumanResource\HeadCountByAfp::class,
            // \App\Filament\App\Widgets\HumanResource\HeadCountByArs::class,
            // MonthlyEmployees::class,
            // MonthlyAttrition::class,
        ];
    }
}
