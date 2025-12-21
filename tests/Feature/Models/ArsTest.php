<?php

use App\Models\Ars;
use App\Models\Employee;

test('arss model interacts with db table', function () {
    $data = Ars::factory()->make();

    Ars::create($data->toArray());

    $this->assertDatabaseHas(Ars::class, $data->only([
        'name', 'person_of_contact', 'description',
    ]));
});

test('arss model has many social securities', function () {
    $ars = Ars::factory()
        ->hasSocialSecurities(1)
        ->create();

    expect($ars->socialSecurities->first())->toBeInstanceOf(\App\Models\SocialSecurity::class);
    expect($ars->socialSecurities())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('arss model has many employees', function () {
    $employee = Employee::factory()->create();
    $ars = Ars::factory()
        ->hasSocialSecurities(1, [
            'employee_id' => $employee->id,
        ])
        ->create();

    expect($ars->employees->first())->toBeInstanceOf(Employee::class);
    expect($ars->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class);
});
