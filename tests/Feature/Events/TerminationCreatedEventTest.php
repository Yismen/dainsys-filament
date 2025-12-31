<?php

use App\Events\TerminationCreatedEvent;
use App\Listeners\SendEmployeeTerminatedEmail;
use Illuminate\Support\Facades\Event;

test('employee terminated event sends notification email', function () {
    Event::fake();
    Event::assertListening(TerminationCreatedEvent::class, SendEmployeeTerminatedEmail::class);
});
