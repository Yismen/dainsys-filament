<?php

use App\Models\Afp;
use App\Models\Ars;
use App\Models\Citizenship;
use App\Models\Department;
use App\Models\Downtime;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Information;
use App\Models\LoginName;
use App\Models\PayrollHour;
use App\Models\Position;
use App\Models\Production;
use App\Models\Project;
use App\Models\Site;
use App\Models\SocialSecurity;
use App\Models\Supervisor;
use App\Models\Suspension;
use App\Models\Termination;
use App\Models\Universal;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

test('employee model interacts with employees table', function () {
    Event::fake();
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
        'date_of_birth',
        'cellphone',
        'status',
        'gender',
        'has_kids',
        'citizenship_id',
    ]));
});

test('employee model update full name when saved', function () {
    Mail::fake();
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

// is universal
