<?php

namespace App\Providers\Filament;

use App\Filament\Employee\Pages\EmployeeDashboard;
use App\Filament\Employee\Pages\Login;
use App\Filament\Employee\Pages\MyIncentives;
use App\Filament\Employee\Pages\MyPayrolls;
use App\Http\Middleware\ForcePasswordChange;
use App\Services\FilamentPanelsService;
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

class EmployeePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return FilamentPanelsService::make($panel)
            ->id('employee')
            ->path('employee')
            ->colors([
                'primary' => Color::Cyan,
            ])
            ->login(Login::class)
            ->topNavigation(false)
            ->discoverResources(in: app_path('Filament/Employee/Resources'), for: 'App\\Filament\\Employee\\Resources')
            ->discoverPages(in: app_path('Filament/Employee/Pages'), for: 'App\\Filament\\Employee\\Pages')
            ->discoverWidgets(in: app_path('Filament/Employee/Widgets'), for: 'App\\Filament\\Employee\\Widgets')
            ->pages([
                EmployeeDashboard::class,
                MyIncentives::class,
                MyPayrolls::class,
            ])
            ->plugins([
                BreezyCore::make()
                    ->myProfile(),
            ]);
    }
}
