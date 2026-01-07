<?php

use App\Console\Commands\Birthdays;
use Illuminate\Foundation\Application;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\UpdateTicketStatus;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Console\Commands\LiveVox\PublishingProductionReport;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(append: [
            \Illuminate\Routing\Middleware\ThrottleRequests::class,
        ]);
        //
    })
    ->withProviders([
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\TelescopeServiceProvider::class,
        App\Providers\Filament\AdminPanelProvider::class,
        App\Providers\Filament\AppPanelProvider::class,
        App\Providers\Filament\SupportPanelProvider::class,
        App\Providers\Filament\HumanResourcePanelProvider::class,
        App\Providers\Filament\WorkforcePanelProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
