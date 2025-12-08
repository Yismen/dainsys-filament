<?php

use App\Models\Employee;
use App\Models\Supervisor;
use App\Models\Information;

test('supervisors model interacts with db table', function () {
    $data = Supervisor::factory()->make();

    Supervisor::create($data->toArray());

    $this->assertDatabaseHas('supervisors', $data->only([
        'name', 'description'
    ]));
});

test('supervisor model uses soft delete', function () {
    $supervisor = Supervisor::factory()->create();

    $supervisor->delete();

    $this->assertSoftDeleted(Supervisor::class, [
        'id' => $supervisor->id
    ]);
});

test('supervisors model morph one information', function () {
    $supervisor = Supervisor::factory()->create();

    expect($supervisor->information)->toBeInstanceOf(Information::class);
    expect($supervisor->information())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphOne::class);
});

test('supervisors model has many employees', function () {
    $supervisor = Supervisor::factory()->create();

    expect($supervisor->employees->first())->toBeInstanceOf(Employee::class);
    expect($supervisor->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
