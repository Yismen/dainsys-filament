<?php

use App\Models\Afp;
use App\Models\Employee;
use App\Models\Information;

test('afps model interacts with db table', function () {
    $data = Afp::factory()->make();

    Afp::create($data->toArray());

    $this->assertDatabaseHas(Afp::class, $data->only([
        'name', 'person_of_contact', 'description'
    ]));
});

test('afps model morph one information', function () {
    $afp = Afp::factory()->create();

    Information::factory()->create([
        'informationable_id' => $afp->id,
        'informationable_type' => Afp::class,
    ]);

    expect($afp->information)->toBeInstanceOf(Information::class);
    expect($afp->information())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphOne::class);
});

test('afps model has many social securities', function () {
    $afp = Afp::factory()
        ->hasSocialSecurities(1)
        ->create();

    expect($afp->socialSecurities->first())->toBeInstanceOf(\App\Models\SocialSecurity::class);
    expect($afp->socialSecurities())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('afps model has many employees', function () {
    $employee = Employee::factory()->create();
    $afp = Afp::factory()
        ->hasSocialSecurities(1, [
            'employee_id' => $employee->id,
        ])
        ->create();

    expect($afp->employees->first())->toBeInstanceOf(Employee::class);
    expect($afp->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class);
});
