<?php

use App\Models\Hire;
use App\Models\Employee;
use App\Events\SuspensionUpdatedEvent;
use App\Events\EmployeeHiredEvent;
use App\Events\TerminationCreatedEvent;
use App\Models\Suspension;
use App\Models\Termination;
use App\Services\BirthdaysService;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    $this->service = new BirthdaysService();

    Event::fake([
        EmployeeHiredEvent::class,
        SuspensionUpdatedEvent::class,
        TerminationCreatedEvent::class,
    ]);
});

test('birthdays service returns birthdays for today', function () {
    $employee_today = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()]);
    $employee_yesterday = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()->subDay()]);

    $birthdays = $this->service->handle('today');

    expect($birthdays->contains('id', $employee_today->id))->toBeTrue();
    expect($birthdays->contains('id', $employee_yesterday->id))->toBeFalse();
});

test('birthdays service returns birthdays for yesterday', function () {
    $employee_yesterday = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()->subDay()]);
    $employee_today = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()]);

    $birthdays = $this->service->handle('yesterday');

    expect($birthdays->contains('id', $employee_yesterday->id))->toBeTrue();
    expect($birthdays->contains('id', $employee_today->id))->toBeFalse();
});

test('birthdays service returns birthdays for tomorrow', function () {
    $employee_tomorrow = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()->addDay()]);
    $employee_today = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()]);

    $birthdays = $this->service->handle('tomorrow');

    expect($birthdays->contains('id', $employee_tomorrow->id))->toBeTrue();
    expect($birthdays->contains('id', $employee_today->id))->toBeFalse();
});

test('birthdays service returns birthdays for this month', function () {
    $employee_this_month = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()->startOfMonth()]);
    $employee_last_month = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()->startOfMonth()->subMonth()]);

    $birthdays = $this->service->handle('this_month');

    expect($birthdays->contains('id', $employee_this_month->id))->toBeTrue();
    expect($birthdays->contains('id', $employee_last_month->id))->toBeFalse();
});

test('birthdays service returns birthdays for last month', function () {
    $employee_this_month = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()->startOfMonth()]);
    $employee_last_month = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()->startOfMonth()->subMonth()]);

    $birthdays = $this->service->handle('last_month');

    expect($birthdays->contains('id', $employee_last_month->id))->toBeTrue();
    expect($birthdays->contains('id', $employee_this_month->id))->toBeFalse();
});

test('birthdays service returns birthdays for next month', function () {
    $employee_this_month = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()]);
    $employee_next_month = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()->addMonth()]);

    $birthdays = $this->service->handle('next_month');

    expect($birthdays->contains('id', $employee_next_month->id))->toBeTrue();
    expect($birthdays->contains('id', $employee_this_month->id))->toBeFalse();
});

test('birthdays service includes suspended employees', function () {
    $suspended_employee = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()]);

    Suspension::factory()->for($suspended_employee)->create();

    $birthdays = $this->service->handle('today');

    expect($birthdays->contains('id', $suspended_employee->id))->toBeTrue();
});

test('birthdays service doesnt include created or unhired employees', function () {
    Employee::factory()
        ->create(['date_of_birth' => now()]);

    $birthdays = $this->service->handle('today');

    expect($birthdays)->toBeEmpty();
});

test('birthdays service doesnt include inactive employees', function () {
    $employee_today = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()]);

    Termination::factory()->for($employee_today)->create();

    $birthdays = $this->service->handle('today');

    expect($birthdays)->toBeEmpty();
});
