<?php

use App\Events\EmployeeReactivatedEvent;
use App\Listeners\SendEmployeeReactivatedEmail;
use Illuminate\Support\Facades\Event;

test('employee reactivated event send notification email', function (): void {
    Event::fake();
    Event::assertListening(EmployeeReactivatedEvent::class, SendEmployeeReactivatedEmail::class);
});
