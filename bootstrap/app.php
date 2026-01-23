<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware
            ->api(append: [
                \Illuminate\Routing\Middleware\ThrottleRequests::class,
            ])
            ->alias([
                'abilities' => CheckAbilities::class,
                'ability' => CheckForAnyAbility::class,
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
        App\Providers\Filament\SupportPanelProvider::class,
        App\Providers\Filament\HumanResourcePanelProvider::class,
        App\Providers\Filament\WorkforcePanelProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
