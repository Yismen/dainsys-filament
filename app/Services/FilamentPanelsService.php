<?php

namespace App\Services;

use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class FilamentPanelsService
{
    public static function make(Panel $panel): Panel
    {
        return $panel
            ->login()
            // ->registration()
            ->passwordReset()
            ->emailVerification()
            ->spa()
            ->favicon(asset('images/ecco-favicon.png'))
            ->databaseNotifications()
            // ->sidebarCollapsibleOnDesktop()
            ->topNavigation()
            ->subNavigationPosition(SubNavigationPosition::Top)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->userMenuItems([
                Action::make('mailing-subscriptions')
                    ->label('Email Subscriptions')
                    ->icon(Heroicon::OutlinedEnvelopeOpen)
                    ->url(fn (): string => route('my-subscriptions')),
                Action::make('it_support')
                    ->label('IT Support')
                    ->icon(Heroicon::OutlinedTicket)
                    ->url(fn (): string => route('my-tickets-management')),
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
