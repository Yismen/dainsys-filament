<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\EventServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\BlogPanelProvider;
use App\Providers\Filament\EmployeePanelProvider;
use App\Providers\Filament\HumanResourcePanelProvider;
use App\Providers\Filament\SupervisorPanelProvider;
use App\Providers\Filament\SupportPanelProvider;
use App\Providers\Filament\WorkforcePanelProvider;
use App\Providers\RouteServiceProvider;
use App\Providers\TelescopeServiceProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    // App\Providers\BroadcastServiceProvider::class,
    EventServiceProvider::class,
    RouteServiceProvider::class,
    TelescopeServiceProvider::class,
    AdminPanelProvider::class,
    SupportPanelProvider::class,
    HumanResourcePanelProvider::class,
    WorkforcePanelProvider::class,
    SupervisorPanelProvider::class,
    EmployeePanelProvider::class,
    BlogPanelProvider::class,
];
