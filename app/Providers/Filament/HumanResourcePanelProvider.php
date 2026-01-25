<?php

namespace App\Providers\Filament;

use App\Filament\HumanResource\Pages\HumanResourceDashboard;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Enums\SubNavigationPosition;
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

class HumanResourcePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('human-resource')
            ->path('human-resource')
            ->login()
            // ->registration()
            ->passwordReset()
            ->emailVerification()
            ->spa()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->subNavigationPosition(SubNavigationPosition::Top)
            ->topNavigation()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(
                in: app_path('Filament/HumanResource/Resources'),
                for: 'App\\Filament\\HumanResource\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/HumanResource/Pages'),
                for: 'App\\Filament\\HumanResource\\Pages'
            )
            ->discoverWidgets(
                in: app_path('Filament/HumanResource/Widgets'),
                for: 'App\\Filament\\HumanResource\\Widgets'
            )
            ->discoverClusters(
                in: app_path('Filament/HumanResource/Clusters'),
                for: 'App\\Filament\\HumanResource\\Clusters'
            )
            ->pages([
                HumanResourceDashboard::class,
            ])
            ->databaseNotifications()
            ->plugins([
                BreezyCore::make()
                    ->myProfile()
                    ->enableTwoFactorAuthentication(),
            ])
            ->userMenuItems([
                Action::make('mailing-subscriptions')
                    ->label('Mailing Subscriptions')
                    ->icon('heroicon-o-rectangle-stack')
                    ->url('admin/user-mailing-subscriptions'),
            ])
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
