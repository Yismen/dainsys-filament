<?php

use App\Filament\Employee\Pages\MyIncentives;
use App\Models\Citizenship;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Incentive;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

uses()->group('employee-panel');

beforeEach(function () {
    Mail::fake();
    $this->citizenship = Citizenship::factory()->create();
});

it('displays incentives data for authenticated employee', function () {
    Mail::fake();

    $employee = Employee::factory()->create([
        'citizenship_id' => $this->citizenship->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subMonths(3),
    ]);

    $employee->refresh();

    $employeeIncentives = Incentive::factory()->count(3)->create(['employee_id' => $employee->id]);
    $otherEmployee = Employee::factory()->create(['citizenship_id' => $this->citizenship->id]);
    Incentive::factory()->count(2)->create(['employee_id' => $otherEmployee->id]);

    $user = User::factory()->create(['employee_id' => $employee->id]);

    $this->actingAs($user);

    $response = $this->get(MyIncentives::getUrl(panel: 'employee'));

    $response->assertSuccessful()
        ->assertSee('My Incentives');

    // Verify the table has 3 records displayed
    $response->assertSee('Showing 1 to 3 of 3');
});

it('prevents access for users without employee_id', function () {
    $user = User::factory()->create([
        'employee_id' => null,
    ]);

    $this->actingAs($user);

    $this->get(MyIncentives::getUrl(panel: 'employee'))
        ->assertForbidden();
});

it('only shows incentive data for authenticated employee', function () {
    Mail::fake();

    $employee1 = Employee::factory()->create([
        'citizenship_id' => $this->citizenship->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employee1->id,
        'date' => now()->subMonths(3),
    ]);

    $employee1->refresh();

    $employee2 = Employee::factory()->create(['citizenship_id' => $this->citizenship->id]);

    $incentives1 = Incentive::factory()->count(2)->create(['employee_id' => $employee1->id]);
    Incentive::factory()->count(2)->create(['employee_id' => $employee2->id]);

    $user = User::factory()->create(['employee_id' => $employee1->id]);

    $this->actingAs($user);

    $response = $this->get(MyIncentives::getUrl(panel: 'employee'));

    $response->assertSuccessful();

    // Verify only employee1's incentives are visible (2 records)
    $response->assertSee('Showing 1 to 2 of 2');
});
