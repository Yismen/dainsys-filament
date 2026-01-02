<?php

use App\Models\Citizenship;
use App\Models\Employee;

test('citizenships model interacts with db table', function () {
    $data = Citizenship::factory()->make();

    Citizenship::create($data->toArray());

    $this->assertDatabaseHas('citizenships', $data->only([
        'name', 'description',
    ]));
});

test('citizenship model has many employees', function () {
    $citizenship = Citizenship::factory()->create();
    $employee = Employee::factory()->create(['citizenship_id' => $citizenship->id]);

    expect($citizenship->employees->first())->toBeInstanceOf(Employee::class);
    expect($citizenship->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
