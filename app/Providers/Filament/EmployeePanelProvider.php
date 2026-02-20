<?php

namespace App\Providers\Filament;

use App\Filament\Employee\Pages\EmployeeDashboard;
use App\Filament\Employee\Pages\Login;
use App\Filament\Employee\Pages\MyIncentives;
use App\Filament\Employee\Pages\MyPayrolls;
use App\Services\FilamentPanelsService;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class EmployeePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return FilamentPanelsService::make($panel)
            ->id('employee')
            ->path('employee')
            ->colors([
                'primary' => Color::Cyan,
            ])
            ->login(Login::class)
            ->topNavigation(false)
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Employee/Resources'), for: 'App\\Filament\\Employee\\Resources')
            ->discoverPages(in: app_path('Filament/Employee/Pages'), for: 'App\\Filament\\Employee\\Pages')
            ->discoverWidgets(in: app_path('Filament/Employee/Widgets'), for: 'App\\Filament\\Employee\\Widgets')
            ->pages([
                EmployeeDashboard::class,
                MyIncentives::class,
                MyPayrolls::class,
            ])
            ->plugins([
                BreezyCore::make()
                    ->myProfile(),
            ]);
    }
}
