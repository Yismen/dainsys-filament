<?php

namespace App\Providers\Filament;

use App\Filament\Support\Pages\Dashboard;
use App\Filament\Support\Pages\SupportDashboard;
use App\Services\FilamentPanelsService;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
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
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class SupportPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return FilamentPanelsService::make($panel)
            ->id('support')
            ->path('support')
            ->plugins([
                BreezyCore::make()
                    ->myProfile()
                    ->enableTwoFactorAuthentication(),
            ])
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Support/Resources'), for: 'App\\Filament\\Support\\Resources')
            ->discoverPages(in: app_path('Filament/Support/Pages'), for: 'App\\Filament\\Support\\Pages')
            ->pages([
                SupportDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Support/Widgets'), for: 'App\\Filament\\Support\\Widgets');
    }
}
