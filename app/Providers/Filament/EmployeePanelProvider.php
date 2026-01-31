<?php

namespace App\Providers\Filament;

use App\Filament\Employee\Pages\EmployeeDashboard;
use App\Filament\Employee\Pages\Login;
use App\Http\Middleware\ForcePasswordChange;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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

class EmployeePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('employee')
            ->path('employee')
            ->colors([
                'primary' => Color::Cyan,
            ])
            ->login(Login::class)
            ->passwordReset()
            ->emailVerification()
            ->spa()
            ->databaseNotifications()
            ->sidebarCollapsibleOnDesktop()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Employee/Resources'), for: 'App\\Filament\\Employee\\Resources')
            ->discoverPages(in: app_path('Filament/Employee/Pages'), for: 'App\\Filament\\Employee\\Pages')
            ->discoverWidgets(in: app_path('Filament/Employee/Widgets'), for: 'App\\Filament\\Employee\\Widgets')
            ->pages([
                Dashboard::class,
            ])
            ->plugins([
                BreezyCore::make()
                    ->myProfile(),
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
                ForcePasswordChange::class,
            ]);
    }
}
