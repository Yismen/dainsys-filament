<?php

use App\Events\EmployeeSaved;
use Illuminate\Support\Facades\Event;
use App\Listeners\UpdateEmployeeFullName;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Listeners\UpdateFullName;

test('employee updated event update full name', function () {
    Event::fake();
    Event::assertListening(EmployeeSaved::class, UpdateEmployeeFullName::class);
});
