<?php

namespace Tests\Feature\Events;

use Tests\TestCase;
use App\Models\Ticket;
use App\Mail\TicketDeletedMail;
use App\Events\TicketDeletedEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use App\Listeners\SendTicketDeletedMail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketDeletedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    // public function event_is_dispatched()
    // {
    //     Event::fake([
    //         TicketDeletedEvent::class
    //     ]);
    //     $this->supportSuperAdminUser();

    //     $ticket = Ticket::factory()->create();

    //     $ticket->delete();

    //     Event::assertDispatched(TicketDeletedEvent::class);
    //     Event::assertListening(
    //         TicketDeletedEvent::class,
    //         SendTicketDeletedMail::class
    //     );
    // }

    /** @test */
    public function when_ticket_is_created_an_email_is_sent()
    {
        Mail::fake();

        $ticket = Ticket::factory()->create();
        $ticket->delete();

        Mail::assertQueued(TicketDeletedMail::class);
    }
}
