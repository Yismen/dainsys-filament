<?php

use App\Models\Campaign;
use App\Models\Source;

test('sources model interacts with db table', function (): void {
    $source = Source::factory()->make();

    Source::create($source->toArray());

    $this->assertDatabaseHas('sources', $source->only([
        'name', 'description',
    ]));
});

test('sources model has many campaigns', function (): void {
    $source = Source::factory()
        ->has(Campaign::factory())
        ->create();

    expect($source->campaigns->first())->toBeInstanceOf(\App\Models\Campaign::class);
    expect($source->campaigns())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
