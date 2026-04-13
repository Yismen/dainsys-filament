<?php

namespace Tests\Feature\Models;

use App\Models\Campaign;
use App\Models\Item;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

test('items model interacts with db table', function (): void {
    $data = Item::factory()->make();

    Item::create($data->toArray());

    $this->assertDatabaseHas('items', $data->only([
        'name', 'campaign_id', 'price', 'description',
    ]));
});

test('item belongs to a campaign', function (): void {
    $item = Item::factory()->create();

    expect($item->campaign)->toBeInstanceOf(Campaign::class);
    expect($item->campaign())->toBeInstanceOf(BelongsTo::class);
});
