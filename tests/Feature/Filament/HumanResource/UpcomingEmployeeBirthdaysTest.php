<?php

use App\Enums\EmployeeStatuses;
use App\Filament\HumanResource\Widgets\UpcomingEmployeeBirthdays;
use App\Models\Employee;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Mail::fake();
    Event::fake();
});

test('upcoming birthdays widget shows employees with birthdays in next 10 days', function () {
    // Create employee with birthday in 5 days
    $employee1 = Employee::factory()->create([
        'status' => EmployeeStatuses::Hired,
        'date_of_birth' => now()->addDays(5)->setYear(1990)->format('Y-m-d'),
    ]);

    // Create employee with birthday tomorrow
    $employee2 = Employee::factory()->create([
        'status' => EmployeeStatuses::Hired,
        'date_of_birth' => now()->addDay()->setYear(1985)->format('Y-m-d'),
    ]);

    // Create employee with birthday yesterday (should not show)
    $employee3 = Employee::factory()->create([
        'status' => EmployeeStatuses::Hired,
        'date_of_birth' => now()->subDay()->setYear(1980)->format('Y-m-d'),
    ]);

    // Create employee with birthday in 11 days (should not show)
    $employee4 = Employee::factory()->create([
        'status' => EmployeeStatuses::Hired,
        'date_of_birth' => now()->addDays(11)->setYear(1992)->format('Y-m-d'),
    ]);

    livewire(UpcomingEmployeeBirthdays::class)
        ->assertOk()
        ->assertCanSeeTableRecords([$employee1, $employee2])
        ->assertCanNotSeeTableRecords([$employee3, $employee4]);
});

test('upcoming birthdays widget excludes terminated employees', function () {
    // Create hired employee with upcoming birthday
    $hiredEmployee = Employee::factory()->create([
        'status' => EmployeeStatuses::Hired,
        'date_of_birth' => now()->addDays(3)->setYear(1988)->format('Y-m-d'),
    ]);

    // Create terminated employee with upcoming birthday
    $terminatedEmployee = Employee::factory()->create([
        'status' => EmployeeStatuses::Terminated,
        'date_of_birth' => now()->addDays(3)->setYear(1990)->format('Y-m-d'),
    ]);

    livewire(UpcomingEmployeeBirthdays::class)
        ->assertOk()
        ->assertCanSeeTableRecords([$hiredEmployee])
        ->assertCanNotSeeTableRecords([$terminatedEmployee]);
});

test('upcoming birthdays widget can be rendered', function () {
    Employee::factory()->create([
        'status' => EmployeeStatuses::Hired,
        'date_of_birth' => now()->addDays(5)->setYear(1990)->format('Y-m-d'),
    ]);

    livewire(UpcomingEmployeeBirthdays::class)
        ->assertOk()
        ->assertSee('Upcoming Employee Birthdays');
});
