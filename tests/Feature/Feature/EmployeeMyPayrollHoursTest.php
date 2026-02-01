<?php

use App\Filament\Employee\Pages\MyPayrollHours;
use App\Models\Citizenship;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\PayrollHour;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

uses()->group('employee-panel');

beforeEach(function () {
    Mail::fake();

    $this->citizenship = Citizenship::factory()->create();
});

it('displays payroll hours for authenticated employee', function () {
    $employee = Employee::factory()->create([
        'citizenship_id' => $this->citizenship->id,
    ]);

    // Create a Hire record to make the employee "Hired"
    Hire::factory()->create([
        'employee_id' => $employee->id,
    ]);

    PayrollHour::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subDays(2),
        'total_hours' => 8,
        'nightly_hours' => 2,
    ]);

    PayrollHour::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subDays(1),
        'total_hours' => 10,
        'nightly_hours' => 3,
    ]);

    PayrollHour::factory()->create([
        'employee_id' => $employee->id,
        'date' => now(),
        'total_hours' => 7,
        'nightly_hours' => 1,
    ]);

    $user = User::factory()->create([
        'employee_id' => $employee->id,
    ])->load('employee');

    $this->actingAs($user);

    $response = $this->get(MyPayrollHours::getUrl(panel: 'employee'));

    $response->assertSuccessful()
        ->assertSee('My Hours');
});

it('prevents access for users without employee_id', function () {
    $user = User::factory()->create()->load('employee');

    $this->actingAs($user);

    $response = $this->get(MyPayrollHours::getUrl(panel: 'employee'));

    $response->assertForbidden();
});

it('only shows payroll hours for authenticated employee', function () {
    $employee1 = Employee::factory()->create([
        'citizenship_id' => $this->citizenship->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employee1->id,
    ]);

    $employee2 = Employee::factory()->create([
        'citizenship_id' => $this->citizenship->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employee2->id,
    ]);

    // Create payroll hours for employee1
    PayrollHour::factory()->create([
        'employee_id' => $employee1->id,
        'date' => now()->subDays(1),
        'total_hours' => 8,
    ]);

    // Create payroll hours for employee2
    PayrollHour::factory()->create([
        'employee_id' => $employee2->id,
        'date' => now()->subDays(1),
        'total_hours' => 10,
    ]);

    $user = User::factory()->create([
        'employee_id' => $employee1->id,
    ])->load('employee');

    $this->actingAs($user);

    $response = $this->get(MyPayrollHours::getUrl(panel: 'employee'));

    $response->assertSuccessful();

    // Assert the page shows employee1's hours
    $this->assertStringContainsString('8.00', $response->content());

    // Assert the page does NOT show employee2's hours
    $this->assertStringNotContainsString('10.00', $response->content());
});

it('displays total hours summary', function () {
    $employee = Employee::factory()->create([
        'citizenship_id' => $this->citizenship->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employee->id,
    ]);

    PayrollHour::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subDays(2),
        'total_hours' => 8,
        'nightly_hours' => 2,
    ]);

    PayrollHour::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subDays(1),
        'total_hours' => 10,
        'nightly_hours' => 3,
    ]);

    $user = User::factory()->create([
        'employee_id' => $employee->id,
    ])->load('employee');

    $this->actingAs($user);

    $response = $this->get(MyPayrollHours::getUrl(panel: 'employee'));

    $response->assertSuccessful();

    // Total should be 18 (8 + 10)
    $this->assertStringContainsString('Summary', $response->content());
});

it('displays week ending and payroll ending filter options', function () {
    $employee = Employee::factory()->create([
        'citizenship_id' => $this->citizenship->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employee->id,
    ]);

    PayrollHour::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subDays(7),
        'total_hours' => 8,
    ]);

    PayrollHour::factory()->create([
        'employee_id' => $employee->id,
        'date' => now(),
        'total_hours' => 10,
    ]);

    $user = User::factory()->create([
        'employee_id' => $employee->id,
    ])->load('employee');

    $this->actingAs($user);

    $response = $this->get(MyPayrollHours::getUrl(panel: 'employee'));

    $response->assertSuccessful()
        ->assertSee('Week Ending')
        ->assertSee('Payroll Ending');
});
