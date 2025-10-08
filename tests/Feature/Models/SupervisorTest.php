<?php

use App\Models\Supervisor;

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

test('supervisors model has many employees', function () {
    $supervisor = Supervisor::factory()->create();

    expect($supervisor->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
