<?php

namespace App\Providers\Filament;

use App\Filament\ProjectExecutive\Pages\ProjectExecutiveDashboard;
use App\Services\FilamentPanelsService;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class ProjectExecutivePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return FilamentPanelsService::make($panel)
            ->id('project-executive')
            ->path('project-executive')
            ->colors([
                'primary' => Color::Teal,
            ])
            ->discoverResources(in: app_path('Filament/ProjectExecutive/Resources'), for: 'App\\Filament\\ProjectExecutive\\Resources')
            ->discoverPages(in: app_path('Filament/ProjectExecutive/Pages'), for: 'App\\Filament\\ProjectExecutive\\Pages')
            ->discoverWidgets(in: app_path('Filament/ProjectExecutive/Widgets'), for: 'App\\Filament\\ProjectExecutive\\Widgets')
            ->pages([
                ProjectExecutiveDashboard::class,
            ])
            ->plugins([
                BreezyCore::make()
                    ->myProfile()
                    ->enableTwoFactorAuthentication(),
            ]);
    }
}
