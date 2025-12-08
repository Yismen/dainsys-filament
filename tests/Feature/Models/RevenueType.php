<?php

use App\Models\RevenueType;
use App\Models\Employee;

test('sources model interacts with db table', function () {
    $source = RevenueType::factory()->make();

    RevenueType::create($source->toArray());

    $this->assertDatabaseHas('revenue_types', $source->only([
        'name', 'description'
    ]));
});

test('source model uses soft delete', function () {
    $source = RevenueType::factory()->create();

    $source->delete();

    $this->assertSoftDeleted(RevenueType::class, [
        'id' => $source->id
    ]);
});

test('sources model has many campaigns', function () {
    $source = RevenueType::factory()->create();

    expect($source->campaigns->first())->toBeInstanceOf(\App\Models\Campaign::class);
    expect($source->campaigns())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
