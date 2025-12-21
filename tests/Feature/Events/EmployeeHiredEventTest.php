<?php

use App\Events\EmployeeHiredEvent;
use App\Listeners\SendEmployeeCreatedEmail;
use Illuminate\Support\Facades\Event;

test('employee created event sends email', function () {
    Event::fake([
        EmployeeHiredEvent::class,
    ]);

    Event::assertListening(EmployeeHiredEvent::class, SendEmployeeCreatedEmail::class);
});
