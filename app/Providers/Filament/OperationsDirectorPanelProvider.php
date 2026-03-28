<?php

namespace App\Providers\Filament;

use App\Filament\OperationsDirector\Pages\OperationsDirectorDashboard;
use App\Services\FilamentPanelsService;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class OperationsDirectorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return FilamentPanelsService::make($panel)
            ->id('operations-director')
            ->path('operations-director')
            ->colors([
                'primary' => Color::Teal,
            ])
            ->discoverResources(in: app_path('Filament/OperationsDirector/Resources'), for: 'App\\Filament\\OperationsDirector\\Resources')
            ->discoverPages(in: app_path('Filament/OperationsDirector/Pages'), for: 'App\\Filament\\OperationsDirector\\Pages')
            ->discoverWidgets(in: app_path('Filament/OperationsDirector/Widgets'), for: 'App\\Filament\\OperationsDirector\\Widgets')
            ->pages([
                OperationsDirectorDashboard::class,
            ])
            ->plugins([
                BreezyCore::make()
                    ->myProfile()
                    ->enableTwoFactorAuthentication(),
            ]);
    }
}
