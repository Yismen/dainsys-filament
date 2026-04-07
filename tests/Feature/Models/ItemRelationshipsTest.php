<?php

use App\Models\Campaign;
use App\Models\Item;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('item belongs to a campaign', function (): void {
    $campaign = Campaign::factory()->create();
    $item = Item::factory()->create(['campaign_id' => $campaign->id]);
    expect($item->campaign)->toBeInstanceOf(Campaign::class);
    expect($item->campaign())->toBeInstanceOf(BelongsTo::class);
});

test('campaign has many items', function (): void {
    $campaign = Campaign::factory()->create();
    Item::factory()->count(2)->create(['campaign_id' => $campaign->id]);
    $campaign->refresh();
    expect($campaign->items)->toHaveCount(2);
    expect($campaign->items())->toBeInstanceOf(HasMany::class);
});
