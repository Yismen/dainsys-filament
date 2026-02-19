<?php

namespace App\Providers\Filament;

use App\Filament\Supervisor\Pages\SupervisorDashboard;
use App\Services\FilamentPanelsService;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class SupervisorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return FilamentPanelsService::make($panel)
            ->id('supervisor')
            ->path('supervisor')
            ->colors([
                'primary' => Color::Blue,
            ])

            ->topNavigation(false)
            ->discoverResources(in: app_path('Filament/Supervisor/Resources'), for: 'App\\Filament\\Supervisor\\Resources')
            ->discoverPages(in: app_path('Filament/Supervisor/Pages'), for: 'App\\Filament\\Supervisor\\Pages')
            ->discoverWidgets(in: app_path('Filament/Supervisor/Widgets'), for: 'App\\Filament\\Supervisor\\Widgets')
            ->pages([
                SupervisorDashboard::class,
            ])
            ->plugins([
                BreezyCore::make()
                    ->myProfile()
                    ->enableTwoFactorAuthentication(),
            ]);
    }
}
