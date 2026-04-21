<?php

use App\Events\EmployeeHiredEvent;
use App\Events\EmployeeSuspendedEvent;
use App\Events\EmployeeTerminatedEvent;
use App\Models\Employee;
use App\Models\Suspension;
use App\Models\Termination;
use App\Services\BirthdaysService;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Event;

beforeEach(function (): void {
    Event::fake([
        EmployeeHiredEvent::class,
        EmployeeSuspendedEvent::class,
        EmployeeTerminatedEvent::class,
    ]);
});

function birthdaysService()
{
    return new BirthdaysService;
}

test('birthdays service returns birthdays for today', function (): void {
    $employee_today = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()]);
    $employee_yesterday = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()->subDay()]);

    $birthdays = birthdaysService()->handle('today');

    expect($birthdays->contains('id', $employee_today->id))->toBeTrue();
    expect($birthdays->contains('id', $employee_yesterday->id))->toBeFalse();
});

test('birthdays service returns birthdays for yesterday', function (): void {
    $employee_yesterday = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()->subDay()]);
    $employee_today = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()]);

    $birthdays = birthdaysService()->handle('yesterday');

    expect($birthdays->contains('id', $employee_yesterday->id))->toBeTrue();
    expect($birthdays->contains('id', $employee_today->id))->toBeFalse();
});

test('birthdays service returns birthdays for tomorrow', function (): void {
    $employee_tomorrow = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()->addDay()]);
    $employee_today = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()]);

    $birthdays = birthdaysService()->handle('tomorrow');

    expect($birthdays->contains('id', $employee_tomorrow->id))->toBeTrue();
    expect($birthdays->contains('id', $employee_today->id))->toBeFalse();
});

test('birthdays service returns birthdays for this month', function (): void {
    $employee_this_month = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()->startOfMonth()]);
    $employee_last_month = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()->startOfMonth()->subMonth()]);

    $birthdays = birthdaysService()->handle('this_month');

    expect($birthdays->contains('id', $employee_this_month->id))->toBeTrue();
    expect($birthdays->contains('id', $employee_last_month->id))->toBeFalse();
});

test('birthdays service returns birthdays for last month', function (): void {
    $employee_this_month = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()->startOfMonth()]);
    $employee_last_month = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()->startOfMonth()->subMonth()]);

    $birthdays = birthdaysService()->handle('last_month');

    expect($birthdays->contains('id', $employee_last_month->id))->toBeTrue();
    expect($birthdays->contains('id', $employee_this_month->id))->toBeFalse();
});

test('birthdays service returns birthdays for next month', function (): void {
    $employee_this_month = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()]);

    $nextMonthDate = now()->startOfMonth()->addMonth()->addDay();

    $employee_next_month = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => $nextMonthDate]);

    $birthdays = birthdaysService()->handle('next_month');

    expect($birthdays->contains('id', $employee_next_month->id))->toBeTrue();
    expect($birthdays->contains('id', $employee_this_month->id))->toBeFalse();
});

test('birthdays service includes suspended employees', function (): void {
    $suspended_employee = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()]);

    Suspension::factory()->for($suspended_employee)->create();

    $birthdays = birthdaysService()->handle('today');

    expect($birthdays->contains('id', $suspended_employee->id))->toBeTrue();
});

test('birthdays service doesnt include created or unhired employees', function (): void {
    Employee::factory()
        ->create(['date_of_birth' => now()]);

    $birthdays = birthdaysService()->handle('today');

    expect($birthdays)->toBeEmpty();
});

test('birthdays service doesnt include inactive employees', function (): void {
    $employee_today = Employee::factory()
        ->hasHires()
        ->create(['date_of_birth' => now()]);

    Termination::factory()->for($employee_today)->create();

    $birthdays = birthdaysService()->handle('today');

    expect($birthdays)->toBeEmpty();
});

test('between() returns birthdays within a single month range', function (): void {
    $today = now()->setMonth(4)->setDay(21);
    $end = now()->setMonth(4)->setDay(25);

    $in_range = Employee::factory()->hasHires()->create(['date_of_birth' => $today->copy()->setYear(1990)]);
    $out_of_range = Employee::factory()->hasHires()->create(['date_of_birth' => $today->copy()->subDays(2)->setYear(1990)]);

    $results = birthdaysService()->between($today, $end);
    expect($results->contains('id', $in_range->id))->toBeTrue();
    expect($results->contains('id', $out_of_range->id))->toBeFalse();
});

test('between() returns birthdays across month boundary', function (): void {
    $start = now()->setMonth(4)->setDay(28);
    $end = now()->setMonth(5)->setDay(2);

    $april_29 = Employee::factory()->hasHires()->create(['date_of_birth' => $start->copy()->addDay()->setYear(1990)]);
    $may_1 = Employee::factory()->hasHires()->create(['date_of_birth' => $end->copy()->subDay()->setYear(1990)]);
    $april_27 = Employee::factory()->hasHires()->create(['date_of_birth' => $start->copy()->subDay()->setYear(1990)]);

    $results = birthdaysService()->between($start, $end);
    expect($results->contains('id', $april_29->id))->toBeTrue();
    expect($results->contains('id', $may_1->id))->toBeTrue();
    expect($results->contains('id', $april_27->id))->toBeFalse();
});

test('between() returns birthdays across year boundary', function (): void {
    $start = now()->setMonth(12)->setDay(29);
    $end = now()->setMonth(1)->setDay(3);

    $dec_30 = Employee::factory()->hasHires()->create(['date_of_birth' => $start->copy()->addDay()->setYear(1990)]);
    $jan_2 = Employee::factory()->hasHires()->create(['date_of_birth' => $end->copy()->subDay()->setYear(1990)]);
    $dec_28 = Employee::factory()->hasHires()->create(['date_of_birth' => $start->copy()->subDay()->setYear(1990)]);

    $results = birthdaysService()->between($start, $end);
    expect($results->contains('id', $dec_30->id))->toBeTrue();
    expect($results->contains('id', $jan_2->id))->toBeTrue();
    expect($results->contains('id', $dec_28->id))->toBeFalse();
});

test('between() respects employee status', function (): void {
    $start = now()->setMonth(4)->setDay(21);
    $end = now()->setMonth(4)->setDay(25);

    $active = Employee::factory()->hasHires()->create(['date_of_birth' => $start->copy()->setYear(1990)]);
    $inactive = Employee::factory()->hasHires()->create(['date_of_birth' => $start->copy()->setYear(1990)]);
    Termination::factory()->for($inactive)->create();

    $results = birthdaysService()->between($start, $end);
    expect($results->contains('id', $active->id))->toBeTrue();
    expect($results->contains('id', $inactive->id))->toBeFalse();
});

test('between() works with time travel', function (): void {
    Date::setTestNow('2026-12-28');
    $start = now();
    $end = now()->addDays(7);

    $dec_29 = Employee::factory()->hasHires()->create(['date_of_birth' => '1990-12-29']);
    $jan_2 = Employee::factory()->hasHires()->create(['date_of_birth' => '1990-01-02']);
    $dec_27 = Employee::factory()->hasHires()->create(['date_of_birth' => '1990-12-27']);

    $results = birthdaysService()->between($start, $end);
    expect($results->contains('id', $dec_29->id))->toBeTrue();
    expect($results->contains('id', $jan_2->id))->toBeTrue();
    expect($results->contains('id', $dec_27->id))->toBeFalse();
    Date::setTestNow();
});
