<?php

use App\Events\EmployeeReactivated;
use Illuminate\Support\Facades\Event;
use App\Listeners\SendEmployeeReactivatedEmail;

test('employee reactivated event send notification email', function () {
    Event::fake();
    Event::assertListening(EmployeeReactivated::class, SendEmployeeReactivatedEmail::class);
});
