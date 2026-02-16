<?php

namespace App\Providers\Filament;

use App\Services\FilamentPanelsService;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class MailingSubscriptionPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return FilamentPanelsService::make($panel)
            ->id('mailing-subscription')
            ->path('mailing-subscription')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->topNavigation()
            ->discoverResources(in: app_path('Filament/MailingSubscription/Resources'), for: 'App\\Filament\\MailingSubscription\\Resources')
            ->discoverPages(in: app_path('Filament/MailingSubscription/Pages'), for: 'App\\Filament\\MailingSubscription\\Pages')
            ->discoverWidgets(in: app_path('Filament/MailingSubscription/Widgets'), for: 'App\\Filament\\MailingSubscription\\Widgets')
            ->plugins([
                BreezyCore::make()
                    ->myProfile(),
            ]);
    }
}
