<?php

namespace App\Providers;

use App\Events\EmployeeHiredEvent;
use App\Events\EmployeeReactivatedEvent;
use App\Events\EmployeeSuspendedEvent;
use App\Events\EmployeeTerminatedEvent;
use App\Events\HRActivityRequestCompleted;
use App\Events\HRActivityRequestCreated;
use App\Events\TicketAssignedEvent;
use App\Events\TicketCompletedEvent;
use App\Events\TicketCreatedEvent;
use App\Events\TicketDeletedEvent;
use App\Events\TicketReopenedEvent;
use App\Events\TicketReplyCreatedEvent;
use App\Listeners\SendEmployeeHiredEmail;
use App\Listeners\SendEmployeeReactivatedEmail;
use App\Listeners\SendEmployeeSuspendedEmail;
use App\Listeners\SendEmployeeTerminatedEmail;
use App\Listeners\SendHRActivityRequestCompletedNotification;
use App\Listeners\SendHRActivityRequestCreatedNotification;
use App\Listeners\SendTicketAssignedMail;
use App\Listeners\SendTicketCompletedMail;
use App\Listeners\SendTicketCreatedMail;
use App\Listeners\SendTicketDeletedMail;
use App\Listeners\SendTicketReopenedMail;
use App\Listeners\SendTicketReplyCreatedMail;
use Illuminate\Auth\Events\Registered;
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
        EmployeeHiredEvent::class => [
            SendEmployeeHiredEmail::class,
        ],
        EmployeeSuspendedEvent::class => [
            SendEmployeeSuspendedEmail::class,
        ],
        EmployeeTerminatedEvent::class => [
            SendEmployeeTerminatedEmail::class,
        ],
        EmployeeReactivatedEvent::class => [
            SendEmployeeReactivatedEmail::class,
        ],
        TicketAssignedEvent::class => [
            SendTicketAssignedMail::class,
        ],
        TicketCompletedEvent::class => [
            SendTicketCompletedMail::class,
        ],
        TicketCreatedEvent::class => [
            SendTicketCreatedMail::class,
        ],
        TicketDeletedEvent::class => [
            SendTicketDeletedMail::class,
        ],
        TicketReopenedEvent::class => [
            SendTicketReopenedMail::class,
        ],
        TicketReplyCreatedEvent::class => [
            SendTicketReplyCreatedMail::class,
        ],
        HRActivityRequestCreated::class => [
            SendHRActivityRequestCreatedNotification::class,
        ],
        HRActivityRequestCompleted::class => [
            SendHRActivityRequestCompletedNotification::class,
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
