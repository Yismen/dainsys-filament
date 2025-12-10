<?php

use App\Models\SuspensionType;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake([
        \App\Events\SuspensionUpdated::class,
    ]);
});

test('suspension types model interacts with db table', function () {
    $data = SuspensionType::factory()->make();

    SuspensionType::create($data->toArray());

    $this->assertDatabaseHas('suspension_types', $data->only([
        'name', 'description'
    ]));
});

test('suspension types model has many suspensions', function () {
    $suspension_type = SuspensionType::factory()
        ->has(\App\Models\Suspension::factory(), 'suspensions')
        ->create();

    expect($suspension_type->suspensions->first())->toBeInstanceOf(\App\Models\Suspension::class);
    expect($suspension_type->suspensions())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('suspension types model has many employees', function () {
    $suspension_type = SuspensionType::factory()
        ->has(\App\Models\Suspension::factory(), 'suspensions')
        ->create();

    expect($suspension_type->employees->first())->toBeInstanceOf(\App\Models\Employee::class);
    expect($suspension_type->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class);
});
