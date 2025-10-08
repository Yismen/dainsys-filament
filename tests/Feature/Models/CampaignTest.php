<?php

use App\Models\Campaign;

test('campaigns model interacts with db table', function () {
    $data = Campaign::factory()->make();

    Campaign::create($data->toArray());

    $this->assertDatabaseHas('campaigns', $data->only([
        'name', 'project_id', 'source', 'revenue_type', 'goal', 'rate'
    ]));
});

test('campaign model uses soft delete', function () {
    $campaign = Campaign::factory()->create();

    $campaign->delete();

    $this->assertSoftDeleted(Campaign::class, $campaign->toArray());
});

test('campaigns model belongs to project', function () {
    $campaign = Campaign::factory()->create();

    expect($campaign->project())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('campaigns model has many performances', function () {
    $campaign = Campaign::factory()->create();

    expect($campaign->performances())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
