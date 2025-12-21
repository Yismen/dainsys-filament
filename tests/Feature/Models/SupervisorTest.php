<?php

use App\Models\Employee;
use App\Models\Information;
use App\Models\Supervisor;

beforeEach(function () {
    \Illuminate\Support\Facades\Event::fake([
        \App\Events\EmployeeHiredEvent::class,
    ]);
});

test('supervisors model interacts with db table', function () {
    $data = Supervisor::factory()->make();

    Supervisor::create($data->toArray());

    $this->assertDatabaseHas('supervisors', $data->only([
        'name', 'description',
    ]));
});

test('supervisors model morph one information', function () {
    $supervisor = Supervisor::factory()
        ->hasInformation()
        ->create();

    expect($supervisor->information)->toBeInstanceOf(Information::class);
    expect($supervisor->information())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphOne::class);
});

test('supervisors model has many hires', function () {
    $supervisor = Supervisor::factory()
        ->hasHires()
        ->create();

    expect($supervisor->hires->first())->toBeInstanceOf(\App\Models\Hire::class);
    expect($supervisor->hires())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('supervisor model has many employees', function () {
    $employee = Employee::factory()->create();
    $supervisor = Supervisor::factory()
        ->hasHires(1, [
            'employee_id' => $employee->id,
        ])
        ->create();

    expect($supervisor->employees->first())->toBeInstanceOf(Employee::class);
    expect($supervisor->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class);
});
