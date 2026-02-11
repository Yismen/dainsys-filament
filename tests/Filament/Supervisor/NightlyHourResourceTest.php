<?php

use App\Enums\EmployeeStatuses;
use App\Models\Employee;
use App\Models\NightlyHour;
use App\Models\Supervisor;
use App\Models\User;
use Livewire\Livewire;

test('supervisor can view nightly hours for their employees', function (): void {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = User::factory()->for($supervisor)->create();

    $employee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    NightlyHour::factory()
        ->for($employee)
        ->count(5)
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\NightlyHours\Pages\ListNightlyHours::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords(NightlyHour::all());
});

test('supervisor cannot see terminated employees nightly hours', function (): void {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = User::factory()->for($supervisor)->create();

    $hiredEmployee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $terminatedEmployee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Terminated])
        ->create();

    $hiredHours = NightlyHour::factory()
        ->for($hiredEmployee)
        ->count(3)
        ->create();

    $terminatedHours = NightlyHour::factory()
        ->for($terminatedEmployee)
        ->count(2)
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\NightlyHours\Pages\ListNightlyHours::class)
        ->assertCanSeeTableRecords($hiredHours)
        ->assertCanNotSeeTableRecords($terminatedHours);
});

test('supervisor can filter nightly hours by date range', function (): void {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = User::factory()->for($supervisor)->create();

    $employee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $oldHours = NightlyHour::factory()
        ->for($employee)
        ->state(['date' => now()->subMonth()])
        ->create();

    $recentHours = NightlyHour::factory()
        ->for($employee)
        ->state(['date' => now()])
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\NightlyHours\Pages\ListNightlyHours::class)
        ->filterTable('date', [
            'date_from' => now()->subWeek()->format('Y-m-d'),
            'date_until' => now()->format('Y-m-d'),
        ])
        ->assertCanSeeTableRecords([$recentHours])
        ->assertCanNotSeeTableRecords([$oldHours]);
});

test('supervisor can filter nightly hours by employee', function (): void {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = User::factory()->for($supervisor)->create();

    $employee1 = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $employee2 = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $hours1 = NightlyHour::factory()->for($employee1)->create();
    $hours2 = NightlyHour::factory()->for($employee2)->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\NightlyHours\Pages\ListNightlyHours::class)
        ->filterTable('employee_id', value: $employee1->id)
        ->assertCanSeeTableRecords([$hours1])
        ->assertCanNotSeeTableRecords([$hours2]);
});

test('nightly hours table displays correct columns', function (): void {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = User::factory()->for($supervisor)->create();

    $employee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    NightlyHour::factory()
        ->for($employee)
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\NightlyHours\Pages\ListNightlyHours::class)
        ->assertCanSeeTableColumns([
            'employee.full_name',
            'date',
            'total_hours',
        ]);
});
