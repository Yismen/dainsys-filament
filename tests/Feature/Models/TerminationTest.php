<?php

use App\Models\Hire;
use App\Models\Employee;
use App\Models\Suspension;
use App\Models\Termination;
use Illuminate\Support\Carbon;
use App\Events\EmployeeHiredEvent;
use Illuminate\Support\Facades\Event;
use App\Events\SuspensionUpdatedEvent;
use App\Events\TerminationCreatedEvent;
use App\Exceptions\EmployeeCantBeTerminated;
use App\Exceptions\TerminationDateCantBeLowerThanHireDate;

beforeEach(function () {
    Event::fake([
        TerminationCreatedEvent::class,
        EmployeeHiredEvent::class,
        SuspensionUpdatedEvent::class
    ]);
});

test('terminations model interacts with db table', function () {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDay()]);
    $termination = Termination::factory()->for($employee)->create();

    $this->assertDatabaseHas('terminations', [
        'id' => $termination->id,
        'date' => $termination->date,
        'employee_id' => $termination->employee_id,
        'termination_type' => $termination->termination_type,
        'is_rehireable' => $termination->is_rehireable,
        'comment' => $termination->comment,
    ]);
});

it('casts date as date format Y-m-d', function () {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDay()]);
    $termination = Termination::factory()->for($employee)->create();

    expect($termination->date)->toBeInstanceOf(Carbon::class);
});

it('casts is_rehireable as boolean', function () {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDay()]);
    $termination = Termination::factory()->for($employee)->create(['is_rehireable' => 1]);

    expect($termination->is_rehireable)->toBeTrue();
});

test('terminations model belongs to employee', function () {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDay()]);
    $termination = Termination::factory()->for($employee)->create();

    expect($termination->employee)->toBeInstanceOf(Employee::class);
    expect($termination->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('termination model fires event when created', function () {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDay()]);
    Termination::factory()->for($employee)->create();

    Event::assertDispatched(TerminationCreatedEvent::class);
});

test('employees with status of Created cannot be terminated', function () {

    $employee = Employee::factory()->create();

    Termination::factory()->for($employee)->create();
})->throws(EmployeeCantBeTerminated::class);

test('suspended employee cannot be terminated', function () {

    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();

    Suspension::factory()->for($employee)->create([
        'starts_at' => now(),
        "ends_at" => now()->addDays(10)
    ]);

    Termination::factory()->for($employee)->create(['date' => now()->addDays(2)]);
})->throws(EmployeeCantBeTerminated::class);

test('termination date cannot be prior to hire date', function(){
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()]);

    Termination::factory()->for($employee)->create(['date' => now()->subDay()]);
})->throws(TerminationDateCantBeLowerThanHireDate::class);
