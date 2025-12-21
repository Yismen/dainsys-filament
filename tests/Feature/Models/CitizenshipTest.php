<?php

use App\Models\Citizenship;
use App\Models\Employee;
use App\Models\Information;

test('citizenships model interacts with db table', function () {
    $data = Citizenship::factory()->make();

    Citizenship::create($data->toArray());

    $this->assertDatabaseHas('citizenships', $data->only([
        'name', 'description',
    ]));
});

test('citizenships model morph one information', function () {
    $citizenship = Citizenship::factory()
        ->hasInformation()
        ->create();

    expect($citizenship->information)->toBeInstanceOf(Information::class);
    expect($citizenship->information())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphOne::class);
});

test('citizenship model has many employees', function () {
    $citizenship = Citizenship::factory()->create();
    $employee = Employee::factory()->create(['citizenship_id' => $citizenship->id]);

    expect($citizenship->employees->first())->toBeInstanceOf(Employee::class);
    expect($citizenship->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
