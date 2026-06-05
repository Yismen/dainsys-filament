<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\BlogAdminPanelProvider;
use App\Providers\Filament\EmployeePanelProvider;
use App\Providers\Filament\HumanResourcePanelProvider;
use App\Providers\Filament\InvoicingPanelProvider;
use App\Providers\Filament\OperationsDirectorPanelProvider;
use App\Providers\Filament\ProjectExecutivePanelProvider;
use App\Providers\Filament\QualityAssurancePanelProvider;
use App\Providers\Filament\RecruitmentPanelProvider;
use App\Providers\Filament\SupervisorPanelProvider;
use App\Providers\Filament\SupportPanelProvider;
use App\Providers\Filament\WorkforcePanelProvider;
use App\Providers\RouteServiceProvider;
use App\Providers\TelescopeServiceProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    AdminPanelProvider::class,
    BlogAdminPanelProvider::class,
    EmployeePanelProvider::class,
    HumanResourcePanelProvider::class,
    InvoicingPanelProvider::class,
    OperationsDirectorPanelProvider::class,
    ProjectExecutivePanelProvider::class,
    QualityAssurancePanelProvider::class,
    RecruitmentPanelProvider::class,
    SupervisorPanelProvider::class,
    SupportPanelProvider::class,
    WorkforcePanelProvider::class,
    RouteServiceProvider::class,
    TelescopeServiceProvider::class,
];
