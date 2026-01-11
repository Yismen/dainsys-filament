<?php

use App\Mail\EmployeeTerminatedMail;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Termination;
use Illuminate\Support\Facades\Mail;

beforeEach(function() {
    Mail::fake();
});

it('contains employee info', function () {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $termination = Termination::factory()->for($employee)->create();

    $mailable = new EmployeeTerminatedMail($termination);

    $mailable->assertSeeInText('terminated');
    $mailable->assertSeeInText($employee->full_name);
});
