<?php

use App\Events\SuspensionUpdatedEvent;
use App\Listeners\SendEmployeeSuspendedEmail;
use Illuminate\Support\Facades\Event;

test('suspension updated event send notification email', function () {
    Event::fake();
    Event::assertListening(SuspensionUpdatedEvent::class, SendEmployeeSuspendedEmail::class);
});
