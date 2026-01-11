<?php

use App\Events\EmployeeTerminatedEvent;
use App\Listeners\SendEmployeeTerminatedEmail;
use Illuminate\Support\Facades\Event;

test('employee terminated event sends notification email', function () {
    Event::fake();
    Event::assertListening(EmployeeTerminatedEvent::class, SendEmployeeTerminatedEmail::class);
});
