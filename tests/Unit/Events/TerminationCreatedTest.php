<?php

use App\Events\TerminationCreated;
use App\Listeners\SendEmployeeTerminatedEmail;
use App\Listeners\TerminateEmployee;
use Illuminate\Support\Facades\Event;

test('employee terminated event changes employee status', function () {
    Event::fake();
    Event::assertListening(TerminationCreated::class, TerminateEmployee::class);
});

test('employee terminated event sends notification email', function () {
    Event::fake();
    Event::assertListening(TerminationCreated::class, SendEmployeeTerminatedEmail::class);
});
