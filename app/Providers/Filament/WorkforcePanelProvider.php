<?php

namespace App\Providers\Filament;

use App\Filament\Workforce\Pages\WorkforceDashboard;
use App\Services\FilamentPanelsService;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class WorkforcePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return FilamentPanelsService::make($panel)
            ->id('workforce')
            ->path('workforce')
            ->colors([
                'primary' => Color::Red,
            ])
            ->discoverResources(in: app_path('Filament/Workforce/Resources'), for: 'App\Filament\Workforce\Resources')
            ->discoverPages(in: app_path('Filament/Workforce/Pages'), for: 'App\Filament\Workforce\Pages')
            ->discoverWidgets(in: app_path('Filament/Workforce/Widgets'), for: 'App\Filament\Workforce\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->pages([
                WorkforceDashboard::class,
            ])
            ->plugins([
                BreezyCore::make()
                    ->myProfile()
                    ->enableTwoFactorAuthentication(),
            ]);
    }
}
