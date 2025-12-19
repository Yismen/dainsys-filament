<?php

use App\Events\EmployeeHiredEvent;
use Illuminate\Support\Facades\Event;
use App\Listeners\SendEmployeeCreatedEmail;

test('employee created event sends email', function () {
    Event::fake();

    Event::assertListening(EmployeeHiredEvent::class, SendEmployeeCreatedEmail::class);
});
