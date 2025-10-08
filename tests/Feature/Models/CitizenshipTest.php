<?php

use App\Models\Citizenship;

test('citizenships model interacts with db table', function () {
    $data = Citizenship::factory()->make();

    Citizenship::create($data->toArray());

    $this->assertDatabaseHas('citizenships', $data->only([
        'name', 'description'
    ]));
});

test('citizenship model uses soft delete', function () {
    $citizenship = Citizenship::factory()->create();

    $citizenship->delete();

    $this->assertSoftDeleted(Citizenship::class, $citizenship->toArray());
});

test('citizenships model has many employees', function () {
    $citizenship = Citizenship::factory()->create();

    expect($citizenship->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
