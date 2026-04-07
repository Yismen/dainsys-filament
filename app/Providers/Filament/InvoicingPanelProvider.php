<?php

namespace App\Providers\Filament;

use App\Filament\Invoicing\Pages\InvoicingDashboard;
use App\Services\FilamentPanelsService;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class InvoicingPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return FilamentPanelsService::make($panel)
            ->id('invoicing')
            ->path('invoicing')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Invoicing/Resources'), for: 'App\Filament\Invoicing\Resources')
            ->discoverPages(in: app_path('Filament/Invoicing/Pages'), for: 'App\Filament\Invoicing\Pages')
            ->discoverWidgets(in: app_path('Filament/Invoicing/Widgets'), for: 'App\Filament\Invoicing\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->pages([
                InvoicingDashboard::class,
            ])
            ->plugins([
                BreezyCore::make()
                    ->myProfile()
                    ->enableTwoFactorAuthentication()
                    ->enableSanctumTokens(permissions: ['use-dainsys']),
            ]);
    }
}
