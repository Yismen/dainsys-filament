<?php

use App\Models\Employee;
use App\Events\EmployeeCreated;
use App\Mail\MailEmployeeCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use App\Mail\EmployeeCreated as EmployeeCreatedMail;


test('employee model interacts with employees table', function () {
    Event::fake();
    $data = Employee::factory()->make();

    Employee::create($data->toArray());

    $this->assertDatabaseHas('employees', $data->only([
        'first_name',
        'second_first_name',
        'last_name',
        'second_last_name',
        'personal_id',
        // 'full_name',
        // 'hired_at',
        // 'date_of_birth',
        'cellphone',
        'status',
        'marriage',
        'punch',
        'gender',
        'kids',
        'site_id',
        'project_id',
        'position_id',
        'citizenship_id',
        'supervisor_id',
        'afp_id',
        'ars_id',
    ]));
});

test('employee model uses soft delete', function () {
    Mail::fake();
    $employee = Employee::factory()->create();

    $employee->delete();

    $this->assertSoftDeleted(Employee::class, [
        'id' => $employee->id
    ]);
});

test('employees model morph one information', function () {
    $employee = Employee::factory()->createQuietly();

    expect($employee->information())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphOne::class);
});

test('employees model belongs to site', function () {
    $employee = Employee::factory()->createQuietly();

    expect($employee->site())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('employees model belongs to project', function () {
    $employee = Employee::factory()->createQuietly();

    expect($employee->project())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('employees model belongs to position', function () {
    $employee = Employee::factory()->createQuietly();

    expect($employee->position())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('employees model belongs to department', function () {
    $employee = Employee::factory()->createQuietly();

    expect($employee->department())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('employees model belongs to citizenship', function () {
    $employee = Employee::factory()->createQuietly();

    expect($employee->citizenship())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('employees model belongs to supervisor', function () {
    $employee = Employee::factory()->createQuietly();

    expect($employee->supervisor())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('employees model belongs to afp', function () {
    $employee = Employee::factory()->createQuietly();

    expect($employee->afp())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('employees model belongs to ars', function () {
    $employee = Employee::factory()->createQuietly();

    expect($employee->ars())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('employees model has many terminations', function () {
    $employee = Employee::factory()->createQuietly();

    expect($employee->terminations())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('employees model has many login names', function () {
    $employee = Employee::factory()->createQuietly();

    expect($employee->loginNames())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('employees model has many suspensions', function () {
    $employee = Employee::factory()->createQuietly();

    expect($employee->suspensions())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('employee model fires event when created', function () {
    Mail::fake();
    Event::fake();
    $employee = Employee::factory()->create();

    Event::assertDispatched(EmployeeCreated::class);
});

test('employee model update full name when saved', function () {
    Mail::fake();
    $employee = Employee::factory()->create();

    $name = trim(
        join(' ', array_filter([
            $employee->first_name,
            $employee->second_first_name,
            $employee->last_name,
            $employee->second_last_name,
        ]))
    );

    $this->assertDatabaseHas('employees', ['full_name' => $name]);
});

/** @test */
// public function email_is_sent_when_employee_is_created()
// {
//     Mail::fake();
//     Employee::factory()->create();
//     Mail::assertQueued(EmployeeCreatedMail::class);
// }
