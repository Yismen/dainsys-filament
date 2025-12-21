<?php

use App\Events\EmployeeReactivated;
use App\Listeners\SendEmployeeReactivatedEmail;
use Illuminate\Support\Facades\Event;

test('employee reactivated event send notification email', function () {
    Event::fake();
    Event::assertListening(EmployeeReactivated::class, SendEmployeeReactivatedEmail::class);
});
