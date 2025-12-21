<?php

use App\Events\SuspensionUpdated;
use App\Listeners\SendEmployeeSuspendedEmail;
use App\Listeners\SuspendEmployee;
use Illuminate\Support\Facades\Event;

test('suspension updated event update employee status', function () {
    Event::fake();
    Event::assertListening(SuspensionUpdated::class, SuspendEmployee::class);
});

test('suspension updated event send notification email', function () {
    Event::fake();
    Event::assertListening(SuspensionUpdated::class, SendEmployeeSuspendedEmail::class);
});
