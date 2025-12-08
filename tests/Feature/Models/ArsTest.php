<?php

use App\Models\Ars;
use App\Models\Employee;
use App\Models\Information;

test('arss model interacts with db table', function () {
    $data = Ars::factory()->make();

    Ars::create($data->toArray());

    $this->assertDatabaseHas(Ars::class, $data->only([
        'name', 'person_of_contact', 'description'
    ]));
});

test('ars model uses soft delete', function () {
    $ars = Ars::factory()->create();

    $ars->delete();

    $this->assertSoftDeleted(Ars::class, [
        'id' => $ars->id
    ]);
});

test('arss model morph one information', function () {
    $ars = Ars::factory()->create();

    expect($ars->information)->toBeInstanceOf(Information::class);
    expect($ars->information())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphOne::class);
});

test('arss model has many employees', function () {
    $ars = Ars::factory()->create();

    expect($ars->employees->first())->toBeInstanceOf(Employee::class);
    expect($ars->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
