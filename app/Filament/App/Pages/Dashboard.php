<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // protected static string $view = 'filament.app.pages.dashboard';



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
