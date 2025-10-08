<?php

use App\Events\TerminationCreated;
use App\Listeners\TerminateEmployee;
use Illuminate\Support\Facades\Event;
use App\Listeners\SendEmployeeTerminatedEmail;

test('employee terminated event changes employee status', function () {
    Event::fake();
    Event::assertListening(TerminationCreated::class, TerminateEmployee::class);
});

test('employee terminated event sends notification email', function () {
    Event::fake();
    Event::assertListening(TerminationCreated::class, SendEmployeeTerminatedEmail::class);
});
