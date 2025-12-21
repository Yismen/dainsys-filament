<?php

use App\Mail\TicketDeletedMail;
use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;

test('when ticket is created an email is sent', function () {
    Mail::fake();

    $ticket = Ticket::factory()->create();
    $ticket->delete();

    Mail::assertQueued(TicketDeletedMail::class);
});
