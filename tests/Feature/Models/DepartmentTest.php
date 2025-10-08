<?php

use App\Models\Department;

test('departments model interacts with db table', function () {
    $data = Department::factory()->make();

    Department::create($data->toArray());

    $this->assertDatabaseHas('departments', $data->only([
        'name', 'description'
    ]));
});

test('department model uses soft delete', function () {
    $department = Department::factory()->create();

    $department->delete();

    $this->assertSoftDeleted(Department::class, [
        'id' => $department->id
    ]);
});

test('departments model has many positions', function () {
    $department = Department::factory()->create();

    expect($department->positions())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('departments model has many employees', function () {
    $department = Department::factory()->create();

    expect($department->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class);
});
