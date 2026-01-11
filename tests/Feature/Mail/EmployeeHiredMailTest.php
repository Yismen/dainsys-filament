<?php

use App\Mail\EmployeeHiredMail;
use App\Models\Employee;
use App\Models\Hire;
use Illuminate\Support\Facades\Mail;

beforeEach(function() {
    Mail::fake();
});

it('contains employee info', function () {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $mailable = new EmployeeHiredMail($employee);

    $mailable->assertSeeInText($employee->full_name);
});
