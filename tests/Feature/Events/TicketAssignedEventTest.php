<?php

namespace Tests\Feature\Events;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ticket;
use App\Mail\TicketAssignedMail;
use App\Events\TicketAssignedEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use App\Listeners\SendTicketAssignedMail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketAssignedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_is_dispatched()
    {
        Event::fake([
            TicketAssignedEvent::class
        ]);

        $ticket = Ticket::factory()->create();

        $ticket->assignTo(User::factory()->create());

        Event::assertDispatched(TicketAssignedEvent::class);
        Event::assertListening(
            TicketAssignedEvent::class,
            SendTicketAssignedMail::class
        );
    }

    /** @test */
    public function when_ticket_is_created_an_email_is_sent()
    {
        Mail::fake();

        $ticket = Ticket::factory()->create();
        $ticket->assignTo(User::factory()->create());

        Mail::assertQueued(TicketAssignedMail::class);
    }
}
