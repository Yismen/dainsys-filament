<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use App\Models\User;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Actions\Action;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use App\Filament\App\Pages\UserMailingSubscriptions;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\HumanResource\Pages\HumanResourcesDashboard;

class HumanResourcePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('human-resource')
            ->path('human-resource')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/HumanResource/Resources'), for: 'App\\Filament\\HumanResource\\Resources')
            ->discoverPages(in: app_path('Filament/HumanResource/Pages'), for: 'App\\Filament\\HumanResource\\Pages')
            ->pages([
                HumanResourcesDashboard::class,
                UserMailingSubscriptions::class,
            ])
            // ->userMenuItems([
            //     Action::make('mailing-subscriptions')
            //         ->label('Mailing Subscriptions')
            //         ->icon('heroicon-o-rectangle-stack')
            //         ->url('admin/user-mailing-subscriptions'),
            // ])
            ->discoverWidgets(in: app_path('Filament/HumanResource/Widgets'), for: 'App\\Filament\\HumanResource\\Widgets')
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
            ->collapsibleNavigationGroups()
            ->sidebarCollapsibleOnDesktop()
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
