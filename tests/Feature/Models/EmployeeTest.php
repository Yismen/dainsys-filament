<?php

use App\Models\Afp;
use App\Models\Ars;
use App\Models\Hire;
use App\Models\Site;
use App\Models\Project;
use App\Models\Downtime;
use App\Models\Employee;
use App\Models\Position;
use App\Models\LoginName;
use App\Models\Universal;
use App\Models\Department;
use App\Models\Production;
use App\Models\Supervisor;
use App\Models\Suspension;
use App\Models\Citizenship;
use App\Models\Information;
use App\Models\PayrollHour;
use App\Models\Termination;
use App\Models\SocialSecurity;
use App\Models\SuspensionType;
use App\Enums\EmployeeStatuses;
use App\Events\SuspensionUpdatedEvent;
use App\Events\EmployeeHiredEvent;
use App\Events\TerminationCreatedEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use App\Exceptions\EmployeeCantBeSuspended;
use App\Exceptions\EmployeeCantBeTerminated;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Exceptions\SuspensionDateCantBeLowerThanHireDate;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use App\Exceptions\TerminationDateCantBeLowerThanHireDate;

beforeEach(function () {
    Mail::fake();
    Event::fake([
        SuspensionUpdatedEvent::class,
        TerminationCreatedEvent::class,
        EmployeeHiredEvent::class,
    ]);
});

test('employee model interacts with employees table', function () {
    $data = Employee::factory()->make();

    Employee::create($data->toArray());

    $this->assertDatabaseHas('employees', $data->only([
        'first_name',
        'second_first_name',
        'last_name',
        'second_last_name',
        'personal_id_type',
        'personal_id',
        // 'full_name',
        // 'date_of_birth',
        'cellphone',
        // 'status',
        'gender',
        'has_kids',
        'citizenship_id',
    ]));
});

test('employee model update full name when saved', function () {
    $employee = Employee::factory()->create();

    $name = trim(
        implode(' ', array_filter([
            $employee->first_name,
            $employee->second_first_name,
            $employee->last_name,
            $employee->second_last_name,
        ]))
    );

    $this->assertDatabaseHas('employees', [
        'id' => $employee->id,
        'full_name' => $name,
    ]);
});

test('employees model morph one information', function () {
    $employee = Employee::factory()->createQuietly();

    Information::factory()->create([
        'informationable_id' => $employee->id,
        'informationable_type' => Employee::class,
    ]);

    expect($employee->information)->toBeInstanceOf(Information::class);
    expect($employee->information())->toBeInstanceOf(MorphOne::class);
});

it('has many', function (string $modelClass, string $relationMethod) {
    $employee = Employee::factory()->createQuietly();

    $modelClass::factory()->for($employee)->createQuietly();

    expect($employee->$relationMethod->first())->toBeInstanceOf($modelClass);
    expect($employee->$relationMethod())->toBeInstanceOf(HasMany::class);
})->with([
    [Production::class, 'productions'],
    [Downtime::class, 'downtimes'],
    [LoginName::class, 'loginNames'],
    [Hire::class, 'hires'],
    [Suspension::class, 'suspensions'],
    [Termination::class, 'terminations'],
    [PayrollHour::class, 'payrollHours'],
]);

test('employees model thru hire belongs to', function (string $modelClass, string $relationMethod) {
    $employee = Employee::factory()->createQuietly();

    Hire::factory()->for($employee)->for($modelClass::factory())->createQuietly();

    expect($employee->$relationMethod)->toBeInstanceOf($modelClass);
    expect($employee->$relationMethod())->toBeInstanceOf(HasOneThrough::class);
})->with([
    [Site::class, 'site'],
    [Project::class, 'project'],
    [Position::class, 'position'],
    // [Department::class, 'department'],
    [Supervisor::class, 'supervisor'],
]);

test('employees model thru social security belongs to ', function (string $modelClass, string $relationMethod) {
    $employee = Employee::factory()->createQuietly();

    SocialSecurity::factory()->for($employee)->for($modelClass::factory())->createQuietly();

    expect($employee->$relationMethod)->toBeInstanceOf($modelClass);
    expect($employee->$relationMethod())->toBeInstanceOf(HasOneThrough::class);
})->with([
    [Afp::class, 'afp'],
    [Ars::class, 'ars'],
    // [Universal::class, 'universal'],
]);

test('employees model belongs to citizenship', function () {
    $employee = Employee::factory()->createQuietly();

    expect($employee->citizenship)->toBeInstanceOf(Citizenship::class);
    expect($employee->citizenship())->toBeInstanceOf(BelongsTo::class);
});

it('sets status as Created by default when employee is created', function () {
    $employee = Employee::factory()->create();

    $this->assertEquals($employee->status, EmployeeStatuses::Created);
});

it('sets status to Hired when employee is hired', function () {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();

    $this->assertEquals($employee->fresh()->status, EmployeeStatuses::Hired);
});

it('sets status to Suspended when employee is suspended', function () {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDays(10)]);

    Suspension::factory()->for($employee)->create(['starts_at' => now()]);

    $this->assertEquals($employee->fresh()->status, EmployeeStatuses::Suspended);
});

it('sets status as Terminated when employee is terminated', function () {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDays(10)]);
    Termination::factory()->for($employee)->create(['date' => now()]);

    $this->assertEquals($employee->fresh()->status, EmployeeStatuses::Terminated);
});
