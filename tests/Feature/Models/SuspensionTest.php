<?php

use App\Models\Hire;
use App\Models\Employee;
use App\Models\Suspension;
use App\Models\Termination;
use App\Events\SuspensionUpdated;
use App\Events\EmployeeHiredEvent;
use App\Events\TerminationCreated;
use Illuminate\Support\Facades\Event;
use App\Exceptions\EmployeeCantBeSuspended;
use App\Exceptions\SuspensionDateCantBeLowerThanHireDate;

beforeEach(function () {
    Event::fake([
        SuspensionUpdated::class,
        EmployeeHiredEvent::class,
        TerminationCreated::class,
    ]);
});

test('suspensions model interacts with db table', function () {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDay()]);
    $suspension = Suspension::factory()->for($employee)->create();

    $this->assertDatabaseHas('suspensions', [
        'id' => $suspension->id,
        'suspension_type_id' => $suspension->suspension_type_id,
        'starts_at' => $suspension->starts_at->format('Y-m-d'),
        'ends_at' => $suspension->ends_at->format('Y-m-d'),
    ]);
});

test('suspensions model belongs to employee', function (string $relationship) {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDay()]);
    $suspension = Suspension::factory()->for($employee)->create();

    expect($suspension->$relationship())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
})->with([
    'employee',
    'suspensionType'
]);

it('casts fields as date', function ($field) {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDay()]);
    $suspension = Suspension::factory()->for($employee)->create();

    expect($suspension->$field)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
})->with([
    'starts_at',
    'ends_at',
]);

test('suspension model fires event when created', function () {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDay()]);
    Suspension::factory()->for($employee)->create();

    Event::assertDispatched(SuspensionUpdated::class);
});

test('employees with status of Created cannot be suspended', function () {

    $employee = Employee::factory()->create();

    Suspension::factory()->for($employee)->create();
})->throws(EmployeeCantBeSuspended::class);

test('terminated employee cannot be suspended', function () {

    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();

    Termination::factory()->for($employee)->create();

    Suspension::factory()->for($employee)->create();
})->throws(EmployeeCantBeSuspended::class);

test('suspension date cannot be prior to hire date', function(){
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()]);

    Suspension::factory()->for($employee)->create(['starts_at' => now()->subDay()]);
})->throws(SuspensionDateCantBeLowerThanHireDate::class);
