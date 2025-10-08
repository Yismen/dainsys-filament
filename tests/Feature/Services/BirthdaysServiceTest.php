<?php

use App\Models\Employee;
use App\Services\BirthdaysService;



beforeEach(function () {
    $this->service = new BirthdaysService();
    $this->date = now();
});

test('birthdays service returns birthdays for today', function () {
    $employee_today = Employee::factory()->current()->createQuietly(['date_of_birth' => $this->date->copy()]);
    $employee_yesterday = Employee::factory()->current()->createQuietly(['date_of_birth' => $this->date->copy()->subDay()]);

    $birthdays = $this->service->handle('today');

    expect($birthdays->contains('id', $employee_today->id))->toBeTrue();
    expect($birthdays->contains('id', $employee_yesterday->id))->toBeFalse();
});

test('birthdays service returns birthdays for yesterday', function () {
    $employee_yesterday = Employee::factory()->current()->createQuietly(['date_of_birth' => $this->date->copy()->subDay()]);
    $employee_today = Employee::factory()->current()->createQuietly(['date_of_birth' => $this->date->copy()]);

    $birthdays = $this->service->handle('yesterday');

    expect($birthdays->contains('id', $employee_yesterday->id))->toBeTrue();
    expect($birthdays->contains('id', $employee_today->id))->toBeFalse();
});

test('birthdays service returns birthdays for tomorrow', function () {
    $employee_tomorrow = Employee::factory()->current()->createQuietly(['date_of_birth' => $this->date->copy()->addDay()]);
    $employee_today = Employee::factory()->current()->createQuietly(['date_of_birth' => $this->date->copy()]);

    $birthdays = $this->service->handle('tomorrow');

    expect($birthdays->contains('id', $employee_tomorrow->id))->toBeTrue();
    expect($birthdays->contains('id', $employee_today->id))->toBeFalse();
});

test('birthdays service returns birthdays for this month', function () {
    $employee_this_month = Employee::factory()->current()->createQuietly(['date_of_birth' => $this->date->copy()->startOfMonth()]);
    $employee_last_month = Employee::factory()->current()->createQuietly(['date_of_birth' => $this->date->copy()->startOfMonth()->subMonth()]);

    $birthdays = $this->service->handle('this_month');

    expect($birthdays->contains('id', $employee_this_month->id))->toBeTrue();
    expect($birthdays->contains('id', $employee_last_month->id))->toBeFalse();
});

test('birthdays service returns birthdays for last month', function () {
    $employee_this_month = Employee::factory()->current()->createQuietly(['date_of_birth' => $this->date->copy()->startOfMonth()]);
    $employee_last_month = Employee::factory()->current()->createQuietly(['date_of_birth' => $this->date->copy()->startOfMonth()->subMonth()]);

    $birthdays = $this->service->handle('last_month');

    expect($birthdays->contains('id', $employee_last_month->id))->toBeTrue();
    expect($birthdays->contains('id', $employee_this_month->id))->toBeFalse();
});

test('birthdays service returns birthdays for next month', function () {
    $employee_this_month = Employee::factory()->current()->createQuietly(['date_of_birth' => $this->date->copy()]);
    $employee_next_month = Employee::factory()->current()->createQuietly(['date_of_birth' => $this->date->copy()->addMonth()]);

    $birthdays = $this->service->handle('next_month');

    expect($birthdays->contains('id', $employee_next_month->id))->toBeTrue();
    expect($birthdays->contains('id', $employee_this_month->id))->toBeFalse();
});

test('birthdays service includes suspended employees', function () {
    $employee_today = Employee::factory()->suspended()->createQuietly(['date_of_birth' => $this->date->copy()]);

    $birthdays = $this->service->handle('today');

    expect($birthdays->contains('id', $employee_today->id))->toBeTrue();
});

test('birthdays service doesnt include inactive employees', function () {
    $employee_today = Employee::factory()->inactive()->createQuietly(['date_of_birth' => $this->date->copy()]);

    $birthdays = $this->service->handle('today');

    expect($birthdays)->toBeEmpty();
});
