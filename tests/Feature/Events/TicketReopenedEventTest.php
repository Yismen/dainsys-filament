<?php

namespace Tests\Feature\Events;

use Tests\TestCase;
use App\Models\Ticket;
use App\Mail\TicketReopenedMail;
use App\Events\TicketReopenedEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use App\Listeners\SendTicketReopenedMail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketReopenedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_is_dispatched()
    {
        Event::fake([
            TicketReopenedEvent::class
        ]);

        // $this->supportSuperAdminUser();
        $ticket = Ticket::factory()->create();

        $ticket->reopen();

        Event::assertDispatched(TicketReopenedEvent::class);
        Event::assertListening(
            TicketReopenedEvent::class,
            SendTicketReopenedMail::class
        );
    }

    /** @test */
    public function when_ticket_is_created_an_email_is_sent()
    {
        Mail::fake();

        // $this->supportSuperAdminUser();
        $ticket = Ticket::factory()->assigned()->create();
        $ticket->reopen();

        Mail::assertQueued(TicketReopenedMail::class);
    }
}
