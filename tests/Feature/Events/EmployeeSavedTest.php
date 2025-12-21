<?php

use App\Events\EmployeeSaved;
use App\Listeners\UpdateEmployeeFullName;
use Illuminate\Support\Facades\Event;

test('employee updated event update full name', function () {
    Event::fake();
    Event::assertListening(EmployeeSaved::class, UpdateEmployeeFullName::class);
});
