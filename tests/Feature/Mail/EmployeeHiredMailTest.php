<?php

use App\Mail\EmployeeHiredMail;
use App\Models\Employee;
use App\Models\Hire;
use Illuminate\Support\Facades\Mail;

beforeEach(function (): void {
    Mail::fake();
});

it('contains employee info', function (): void {
    $employee = Employee::factory()->create();
    $hire = Hire::factory()->for($employee)->create();
    $mailable = new EmployeeHiredMail($hire);

    $mailable->assertSeeInText('hired');
    $mailable->assertSeeInText($employee->full_name);

});
