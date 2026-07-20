<?php

namespace App\Filament\Recruitment\Pages;

use Filament\Pages\Dashboard;
use Filament\Support\Icons\Heroicon;

class RecruitmentDashboard extends Dashboard
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartBar;

    public static function getNavigationLabel(): string
    {
        return __('filament.dashboard');
    }
}
