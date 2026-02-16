<?php

namespace App\Providers\Filament;

use App\Filament\Workforce\Pages\Dashboard;
use App\Filament\Workforce\Pages\WorkforceDashboard;
use App\Services\FilamentPanelsService;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
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
            ->topNavigation(false)
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
