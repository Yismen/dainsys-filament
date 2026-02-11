<?php

use App\Events\EmployeeHiredEvent;
use App\Events\EmployeeSuspendedEvent;
use App\Events\EmployeeTerminatedEvent;
use App\Exceptions\EmployeeCantBeHired;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Suspension;
use App\Models\Termination;
use Illuminate\Support\Facades\Event;

beforeEach(function (): void {
    Event::fake([
        EmployeeHiredEvent::class,
        EmployeeSuspendedEvent::class,
        EmployeeTerminatedEvent::class,
    ]);
});

test('hires model interacts with db table', function (): void {
    $data = Hire::factory()->make();

    Hire::create($data->toArray());

    $this->assertDatabaseHas('hires', $data->only([
        'employee_id',
        'date',
        'site_id',
        'project_id',
        'position_id',
        'supervisor_id',
    ]));
});

test('hires model belongs to model', function (string $modelClass, string $relationMethod): void {
    $hire = Hire::factory()->create();

    expect($hire->$relationMethod)->toBeInstanceOf($modelClass);
    expect($hire->$relationMethod())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
})->with([
    [\App\Models\Employee::class, 'employee'],
    [\App\Models\Site::class, 'site'],
    [\App\Models\Project::class, 'project'],
    [\App\Models\Position::class, 'position'],
    [\App\Models\Supervisor::class, 'supervisor'],
]);

it('casts fields as date', function (): void {
    $hire = Hire::factory()->create();

    expect($hire->date)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('fires event when a employee hire is created', function (): void {
    Hire::factory()->create();

    Event::assertDispatched(EmployeeHiredEvent::class);
});

test('employees with status of Hired cannot be hired again', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();

    expect(fn () => Hire::factory()->for($employee)->create())
        ->toThrow(EmployeeCantBeHired::class);
});

test('employees with status of Suspended cannot be hired', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    Suspension::factory()->for($employee)->create();

    expect(fn () => Hire::factory()->for($employee)->create())
        ->toThrow(EmployeeCantBeHired::class);
});

test('terminated employee can be hired', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();

    Termination::factory()->for($employee)->create();

    expect(fn () => Hire::factory()->for($employee)->create())
        ->not()
        ->toThrow(EmployeeCantBeHired::class);
});

test('Created employee can be hired', function (): void {
    $employee = Employee::factory()->create();

    expect(fn () => Hire::factory()->for($employee)->create())
        ->not()
        ->toThrow(EmployeeCantBeHired::class);
});

// test('hire date cannot be prior to employee created at date', function(){
//     $employee = Employee::factory()->create();
//     Hire::factory()->for($employee)->create(['date' => now()]);

//     Suspension::factory()->for($employee)->create(['starts_at' => now()->subDay()]);
// })->throws(SuspensionDateCantBeLowerThanHireDate::class);
