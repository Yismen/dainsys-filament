<?php

namespace App\Providers\Filament;

use App\Filament\Recruitment\Pages\RecruitmentDashboard;
use App\Services\FilamentPanelsService;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class RecruitmentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return FilamentPanelsService::make($panel)
            ->id('recruitment')
            ->path('recruitment')
            ->colors([
                'primary' => Color::Teal,
            ])
            ->discoverResources(
                in: app_path('Filament/Recruitment/Resources'),
                for: 'App\\Filament\\Recruitment\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Recruitment/Pages'),
                for: 'App\\Filament\\Recruitment\\Pages'
            )
            ->discoverWidgets(
                in: app_path('Filament/Recruitment/Widgets'),
                for: 'App\\Filament\\Recruitment\\Widgets'
            )
            ->discoverClusters(
                in: app_path('Filament/Recruitment/Clusters'),
                for: 'App\\Filament\\Recruitment\\Clusters'
            )
            ->pages([
                RecruitmentDashboard::class,
            ])
            ->plugins([
                BreezyCore::make()
                    ->myProfile()
                    ->enableTwoFactorAuthentication(),
            ]);
    }
}
