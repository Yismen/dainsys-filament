<?php

use App\Models\Position;
use Illuminate\Support\Facades\Mail;

test('position model interacts with positions table', function () {
    $data = Position::factory()->make();

    Position::create($data->toArray());

    $this->assertDatabaseHas('positions', $data->only([
        'name',
        'department_id',
        'payment_type',
        'salary',
        'description',
    ]));
});

test('position model uses soft delete', function () {
    $position = Position::factory()->create();

    $position->delete();

    $this->assertSoftDeleted(Position::class, [
        'id' => $position->id
    ]);
});

test('positions model has many employees', function () {
    $position = Position::factory()->create();

    expect($position->employees->first())->toBeInstanceOf(\App\Models\Employee::class);
    expect($position->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('positions model belongs to department', function () {
    $position = Position::factory()->create();

    expect($position->department)->toBeInstanceOf(\App\Models\Department::class);
    expect($position->department())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
