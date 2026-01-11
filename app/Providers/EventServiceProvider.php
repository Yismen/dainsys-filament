<?php

namespace App\Providers;

use App\Events\EmployeeHiredEvent;
use App\Events\EmployeeReactivatedEvent;
use App\Events\SuspensionUpdatedEvent;
use App\Events\TerminationCreatedEvent;
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
use App\Listeners\SendTicketAssignedMail;
use App\Listeners\SendTicketCompletedMail;
use App\Listeners\SendTicketCreatedMail;
use App\Listeners\SendTicketDeletedMail;
use App\Listeners\SendTicketReopenedMail;
use App\Listeners\SendTicketReplyCreatedMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        SuspensionUpdatedEvent::class => [
            SendEmployeeSuspendedEmail::class,
        ],
        TerminationCreatedEvent::class => [
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
