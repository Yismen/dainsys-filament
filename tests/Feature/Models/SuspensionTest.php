<?php

use App\Enums\SuspensionStatuses;
use App\Events\EmployeeHiredEvent;
use App\Events\EmployeeSuspendedEvent;
use App\Events\EmployeeTerminatedEvent;
use App\Exceptions\EmployeeCantBeSuspended;
use App\Exceptions\SuspensionDateCantBeLowerThanHireDate;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Suspension;
use App\Models\Termination;
use Illuminate\Support\Facades\Event;

beforeEach(function (): void {
    Event::fake([
        EmployeeSuspendedEvent::class,
        EmployeeHiredEvent::class,
        EmployeeTerminatedEvent::class,
    ]);
});

test('suspensions model interacts with db table', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDay()]);
    $suspension = Suspension::factory()->for($employee)->create();

    $this->assertDatabaseHas('suspensions', [
        'id' => $suspension->id,
        'suspension_type_id' => $suspension->suspension_type_id,
        'starts_at' => $suspension->starts_at,
        'ends_at' => $suspension->ends_at,
        'status' => $suspension->status,
        'comment' => $suspension->comment,
    ]);
});

test('suspensions model belongs to employee', function (string $relationship): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDay()]);
    $suspension = Suspension::factory()->for($employee)->create();

    expect($suspension->$relationship())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
})->with([
    'employee',
    'suspensionType',
]);

it('casts fields as date', function ($field): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDay()]);
    $suspension = Suspension::factory()->for($employee)->create();

    expect($suspension->$field)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
})->with([
    'starts_at',
    'ends_at',
]);

test('suspension model fires event when created', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDay()]);
    Suspension::factory()->for($employee)->create();

    Event::assertDispatched(EmployeeSuspendedEvent::class);
});

test('employees with status of Created cannot be suspended', function (): void {

    $employee = Employee::factory()->create();

    Suspension::factory()->for($employee)->create();
})->throws(EmployeeCantBeSuspended::class);

test('terminated employee cannot be suspended', function (): void {

    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();

    Termination::factory()->for($employee)->create();

    Suspension::factory()->for($employee)->create();
})->throws(EmployeeCantBeSuspended::class);

test('suspension date cannot be prior to hire date', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()]);

    Suspension::factory()->for($employee)->create(['starts_at' => now()->subDay()]);
})->throws(SuspensionDateCantBeLowerThanHireDate::class);

it('casts status as instance of Suspensionstatuses', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDays(10)]);

    $suspension = Suspension::factory()->for($employee)->create();

    expect($suspension->status)
        ->toBeInstanceOf(SuspensionStatuses::class);

});

it('sets status to Pending if starts at is future', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDays(10)]);

    $suspension = Suspension::factory()->for($employee)->create([
        'starts_at' => now()->addDay(),
        'ends_at' => now()->addDay(),
    ]);

    expect($suspension->status)
        ->toBe(SuspensionStatuses::Pending);
});

it('sets status to Current if starts at is past and ends at is future', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDays(10)]);

    $suspension = Suspension::factory()->for($employee)->create([
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addDay(),
    ]);

    expect($suspension->status)
        ->toBe(SuspensionStatuses::Current);
});

it('sets status to Completed if ends at is past', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDays(10)]);

    $suspension = Suspension::factory()->for($employee)->create([
        'starts_at' => now()->subDay(),
        'ends_at' => now()->subDay(),
    ]);

    expect($suspension->status)
        ->toBe(SuspensionStatuses::Completed);
});
