<?php

use App\Models\Ticket;
use App\Mail\TicketDeletedMail;
use App\Events\TicketDeletedEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use App\Listeners\SendTicketDeletedMail;

test('when ticket is created an email is sent', function () {
    Mail::fake();

    $ticket = Ticket::factory()->create();
    $ticket->delete();

    Mail::assertQueued(TicketDeletedMail::class);
});
