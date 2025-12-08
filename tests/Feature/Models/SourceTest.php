<?php

use App\Models\Source;
use App\Models\Employee;

test('sources model interacts with db table', function () {
    $source = Source::factory()->make();

    Source::create($source->toArray());

    $this->assertDatabaseHas('sources', $source->only([
        'name', 'description'
    ]));
});

test('source model uses soft delete', function () {
    $source = Source::factory()->create();

    $source->delete();

    $this->assertSoftDeleted(Source::class, [
        'id' => $source->id
    ]);
});

test('sources model has many campaigns', function () {
    $source = Source::factory()->create();

    expect($source->campaigns->first())->toBeInstanceOf(\App\Models\Campaign::class);
    expect($source->campaigns())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
