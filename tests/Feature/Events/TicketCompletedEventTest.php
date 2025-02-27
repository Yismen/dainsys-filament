<?php

namespace Tests\Feature\Events;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ticket;
use App\Mail\TicketCompletedMail;
use App\Events\TicketCompletedEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use App\Listeners\SendTicketCompletedMail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketCompletedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_is_dispatched()
    {
        Event::fake([
            TicketCompletedEvent::class
        ]);

        // $this->supportSuperAdminUser();
        $ticket = Ticket::factory()->create(['owner_id' => User::factory()->create()]);

        $ticket->complete();

        Event::assertDispatched(TicketCompletedEvent::class);
        Event::assertListening(
            TicketCompletedEvent::class,
            SendTicketCompletedMail::class
        );
    }

    /** @test */
    public function when_ticket_is_created_an_email_is_sent()
    {
        Mail::fake();

        $ticket = Ticket::factory()->create(['owner_id' => User::factory()->create()]);
        $ticket->complete();

        Mail::assertQueued(TicketCompletedMail::class);
    }
}
