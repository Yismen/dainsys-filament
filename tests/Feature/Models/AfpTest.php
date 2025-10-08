<?php

use App\Models\Afp;

test('afps model interacts with db table', function () {
    $data = Afp::factory()->make();

    Afp::create($data->toArray());

    $this->assertDatabaseHas(Afp::class, $data->only([
        'name', 'description'
    ]));
});

test('afp model uses soft delete', function () {
    $afp = Afp::factory()->create();

    $afp->delete();

    $this->assertSoftDeleted(Afp::class, [
        'id' => $afp->id
    ]);
});

test('afps model morph one information', function () {
    $afp = Afp::factory()->create();

    expect($afp->information())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphOne::class);
});

test('afps model has many employees', function () {
    $afp = Afp::factory()->create();

    expect($afp->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
