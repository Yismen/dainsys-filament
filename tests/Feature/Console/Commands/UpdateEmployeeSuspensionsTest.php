<?php

use App\Console\Commands\UpdateEmployeeSuspensions;
use App\Enums\EmployeeStatus;
use App\Models\Employee;
use App\Models\Suspension;

test('install command creates site', function () {
    $this->artisan('dainsys:update-employee-suspensions')
        ->assertSuccessful();
});

test('command is schedulled for daily at 300 am', function () {
    $addedToScheduler = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
        ->filter(function ($element) {
            return str($element->command)->contains('dainsys:update-employee-suspensions');
        })->first();

    expect($addedToScheduler)->not->toBeNull();
    expect($addedToScheduler->expression)->toEqual('0 3 * * *');
});

test('current employees are suspended', function () {
    $current = Employee::factory()->createQuietly();
    Suspension::factory()->createQuietly([
        'employee_id' => $current->id,
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addDay(),
    ]);
    $current->update(['status' => EmployeeStatus::Current]);

    $this->artisan(UpdateEmployeeSuspensions::class);

    $this->assertDatabaseHas('employees', [
        'id' => $current->id,
        'status' => EmployeeStatus::Suspended,
    ]);
});

test('inactive employees are not suspended', function () {
    $current = Employee::factory()->inactive()->createQuietly();
    Suspension::factory()->createQuietly([
        'employee_id' => $current->id,
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addDay(),
    ]);
    $current->update(['status' => EmployeeStatus::Inactive]);

    $this->artisan(UpdateEmployeeSuspensions::class);

    $this->assertDatabaseHas('employees', [
        'id' => $current->id,
        'status' => EmployeeStatus::Inactive,
    ]);
});

test('inactive employees should not be suspended', function () {
    $inactive = Employee::factory()->inactive()->createQuietly();

    Suspension::factory()->createQuietly([
        'employee_id' => $inactive->id,
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addDay(),
    ]);

    $this->artisan(UpdateEmployeeSuspensions::class);

    $this->assertDatabaseMissing('employees', [
        'id' => $inactive->id,
        'status' => EmployeeStatus::Suspended,
    ]);
});

test('employee is not suspended if starts at is before now', function () {
    $current = Employee::factory()->createQuietly();
    Suspension::factory()->createQuietly([
        'employee_id' => $current->id,
        'starts_at' => now()->addDay(),
        'ends_at' => now()->addDay(),
    ]);
    $current->update(['status' => EmployeeStatus::Current]);

    $this->artisan(UpdateEmployeeSuspensions::class);

    $this->assertDatabaseHas('employees', [
        'id' => $current->id,
        'status' => EmployeeStatus::Current,
    ]);
});

test('employee is not suspended if ends at is after now', function () {
    $current = Employee::factory()->createQuietly();
    Suspension::factory()->createQuietly([
        'employee_id' => $current->id,
        'starts_at' => now()->subDay(),
        'ends_at' => now()->subDay(),
    ]);
    $current->update(['status' => EmployeeStatus::Current]);

    $this->artisan(UpdateEmployeeSuspensions::class);

    $this->assertDatabaseHas('employees', [
        'id' => $current->id,
        'status' => EmployeeStatus::Current,
    ]);
});

test('suspended employees are activated if today is prior to starts at', function () {
    $current = Employee::factory()->suspended()->createQuietly();
    Suspension::factory()->createQuietly([
        'employee_id' => $current->id,
        'starts_at' => now()->addDay(),
        'ends_at' => now()->addDay(),
    ]);
    $current->update(['status' => EmployeeStatus::Suspended]);

    $this->artisan(UpdateEmployeeSuspensions::class);

    $this->assertDatabaseHas('employees', [
        'id' => $current->id,
        'status' => EmployeeStatus::Current,
    ]);
});

test('suspended employees are activated if today is after ends at', function () {
    $current = Employee::factory()->suspended()->createQuietly();
    Suspension::factory()->createQuietly([
        'employee_id' => $current->id,
        'starts_at' => now()->subDay(),
        'ends_at' => now()->subDay(),
    ]);
    $current->update(['status' => EmployeeStatus::Suspended]);

    $this->artisan(UpdateEmployeeSuspensions::class);

    $this->assertDatabaseHas('employees', [
        'id' => $current->id,
        'status' => EmployeeStatus::Current,
    ]);
});

/** @test */
// public function command_is_schedulled_for_evey_thirty_minutes()
// {
//     $addedToScheduler = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
//         ->filter(function ($element) {
//             return str($element->command)->contains('support:update-ticket-status');
//         })->first();
//     $this->assertNotNull($addedToScheduler);
//     $this->assertEquals('0,30 * * * *', $addedToScheduler->expression);
// }
