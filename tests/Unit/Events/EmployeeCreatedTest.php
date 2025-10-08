<?php

use App\Events\EmployeeCreated;
use Illuminate\Support\Facades\Event;
use App\Listeners\SendEmployeeCreatedEmail;

test('employee created event sends email', function () {
    Event::fake();

    Event::assertListening(EmployeeCreated::class, SendEmployeeCreatedEmail::class);
});
