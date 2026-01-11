<?php

use App\Events\EmployeeSuspendedEvent;
use App\Listeners\SendEmployeeSuspendedEmail;
use Illuminate\Support\Facades\Event;

test('suspension updated event send notification email', function () {
    Event::fake();
    Event::assertListening(EmployeeSuspendedEvent::class, SendEmployeeSuspendedEmail::class);
});
