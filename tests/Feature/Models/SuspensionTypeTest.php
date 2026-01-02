<?php

use App\Events\EmployeeHiredEvent;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Suspension;
use App\Models\SuspensionType;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake([
        \App\Events\SuspensionUpdatedEvent::class,
        EmployeeHiredEvent::class,
    ]);
});

test('suspension types model interacts with db table', function () {
    $data = SuspensionType::factory()->make();

    SuspensionType::create($data->toArray());

    $this->assertDatabaseHas('suspension_types', $data->only([
        'name', 'description',
    ]));
});

test('suspension types model has many suspensions', function () {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $suspension_type = SuspensionType::factory()
        ->create();
    Suspension::factory()->for($employee)->for($suspension_type)->create();

    expect($suspension_type->suspensions->first())->toBeInstanceOf(Suspension::class);
    expect($suspension_type->suspensions())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('suspension types model has many employees', function () {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $suspension_type = SuspensionType::factory()
        ->create();
    Suspension::factory()->for($employee)->for($suspension_type)->create();

    expect($suspension_type->employees->first())->toBeInstanceOf(\App\Models\Employee::class);
    expect($suspension_type->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class);
});
