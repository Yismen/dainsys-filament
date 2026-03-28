<?php

namespace App\Providers\Filament;

use App\Filament\QA\Pages\QADashboard;
use App\Services\FilamentPanelsService;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class QualityAssurancePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return FilamentPanelsService::make($panel)
            ->id('quality-assurance')
            ->path('qa')
            ->colors([
                'primary' => Color::Red,
            ])
            ->discoverResources(in: app_path('Filament/QA/Resources'), for: 'App\\Filament\\QA\\Resources')
            ->discoverPages(in: app_path('Filament/QA/Pages'), for: 'App\\Filament\\QA\\Pages')
            ->discoverWidgets(in: app_path('Filament/QA/Widgets'), for: 'App\\Filament\\QA\\Widgets')
            ->pages([
                QADashboard::class,
            ])
            ->plugins([
                BreezyCore::make()
                    ->myProfile()
                    ->enableTwoFactorAuthentication(),
            ]);
    }
}
