<?php

namespace App\Providers;

use App\Events\EmployeeSaved;
use App\Events\EmployeeCreated;
use App\Events\SuspensionUpdated;
use App\Events\TerminationCreated;
use App\Listeners\SuspendEmployee;
use App\Events\EmployeeReactivated;
use App\Listeners\TerminateEmployee;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\UpdateEmployeeFullName;
use App\Listeners\SendEmployeeCreatedEmail;
use App\Listeners\SendEmployeeSuspendedEmail;
use App\Listeners\SendEmployeeTerminatedEmail;
use App\Listeners\SendEmployeeReactivatedEmail;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        EmployeeSaved::class => [
            UpdateEmployeeFullName::class
        ],
        EmployeeCreated::class => [
            SendEmployeeCreatedEmail::class,
        ],
        SuspensionUpdated::class => [
            SuspendEmployee::class,
            SendEmployeeSuspendedEmail::class,
        ],
        TerminationCreated::class => [
            TerminateEmployee::class,
            SendEmployeeTerminatedEmail::class,
        ],
        EmployeeReactivated::class => [
            SendEmployeeReactivatedEmail::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
