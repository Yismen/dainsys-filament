<?php

use App\Events\EmployeeHiredEvent;
use App\Events\EmployeeSuspendedEvent;
use App\Events\EmployeeTerminatedEvent;
use App\Exceptions\EmployeeCantBeHired;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Position;
use App\Models\Project;
use App\Models\Site;
use App\Models\Supervisor;
use App\Models\Suspension;
use App\Models\Termination;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
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
        'site_id',
        'project_id',
        'position_id',
        'supervisor_id',
    ]));
});

test('hires model belongs to model', function (string $modelClass, string $relationMethod): void {
    $hire = Hire::factory()->create();

    expect($hire->$relationMethod)->toBeInstanceOf($modelClass);
    expect($hire->$relationMethod())->toBeInstanceOf(BelongsTo::class);
})->with([
    [Employee::class, 'employee'],
    [Site::class, 'site'],
    [Project::class, 'project'],
    [Position::class, 'position'],
    [Supervisor::class, 'supervisor'],
]);

it('casts fields as date', function (): void {
    $hire = Hire::factory()->create();

    expect($hire->date)->toBeInstanceOf(Carbon::class);
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
