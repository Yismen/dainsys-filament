<?php

namespace App\Providers\Filament;

use AchyutN\FilamentLogViewer\FilamentLogViewer;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BinaryBuilds\FilamentFailedJobs\FilamentFailedJobsPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
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

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            // ->registration()
            ->passwordReset()
            ->emailVerification()
            ->spa()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->navigationItems([
                NavigationItem::make()
                    ->group('System')
                    ->label('Pulse')
                    ->icon('heroicon-o-bolt')
                    ->url(fn (): string => route('pulse'))
                    ->openUrlInNewTab(),
                NavigationItem::make()
                    ->group('System')
                    ->label('Telescope')
                    ->icon('heroicon-o-cursor-arrow-ripple')
                    ->url(fn (): string => route('telescope'))
                    ->openUrlInNewTab(),
            ])
            ->databaseNotifications()
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup('Access Control')
                    ->globallySearchable(true)
                    ->globalSearchResultsLimit(50)
                    ->forceGlobalSearchCaseInsensitive(true)
                    ->splitGlobalSearchTerms(false)
                    ->modelLabel('Model')
                    ->pluralModelLabel('Models')
                    ->recordTitleAttribute('name')
                    ->titleCaseModelLabel(true),
                FilamentLogViewer::make()
                    ->navigationGroup('System'),
                FilamentFailedJobsPlugin::make()
                    ->navigationGroup('System')
                    ->hideQueueOnIndex(),
                BreezyCore::make()
                    ->myProfile()
                    ->enableTwoFactorAuthentication()
                    ->enableSanctumTokens(permissions: ['use-dainsys']),
            ])
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
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
