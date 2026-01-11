<?php

use App\Mail\EmployeeSuspendedMail;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Suspension;
use Illuminate\Support\Facades\Mail;

beforeEach(function() {
    Mail::fake();
});

it('contains employee info', function () {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $termination = Suspension::factory()->for($employee)->create();

    $mailable = new EmployeeSuspendedMail($termination);

    $mailable->assertSeeInText('suspended');
    $mailable->assertSeeInText($employee->full_name);
});
