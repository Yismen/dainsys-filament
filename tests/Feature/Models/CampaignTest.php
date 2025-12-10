<?php

use App\Models\Source;
use App\Models\Project;
use App\Models\Campaign;

test('campaigns model interacts with db table', function () {
    $data = Campaign::factory()->make();

    Campaign::create($data->toArray());

    $this->assertDatabaseHas('campaigns', $data->only([
        'name', 'project_id', 'source_id', 'revenue_type', 'sph_goal', 'revenue_rate', 'description'
    ]));
});

test('campaigns model belongs to related models', function (string $modelClass, string $relationship) {
    $campaign = Campaign::factory()->create();

    expect($campaign->$relationship)->toBeInstanceOf($modelClass);
    expect($campaign->$relationship())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
})->with([
    [Project::class, 'project'],
    [Source::class, 'source'],
]);

test('campaigns model has many productions', function () {
    $campaign = Campaign::factory()
        ->has(\App\Models\Production::factory())->create();

    expect($campaign->productions->first())->toBeInstanceOf(\App\Models\Production::class);
    expect($campaign->productions())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('campaigns model casts revenue_type to RevenueTypes enum', function () {
    $campaign = Campaign::factory()->create([
        'revenue_type' => 'login time'
    ]);

    expect($campaign->revenue_type)->toBeInstanceOf(\App\Enums\RevenueTypes::class);
    expect($campaign->revenue_type->value)->toBe('login time');
});

// test('isDowntime scope works as expected', function () {
//     $downtimeCampaign = Campaign::factory()->create(['revenue_type' => 'Downtime']);
//     $normalCampaign = Campaign::factory()->create(['revenue_type' => 'Login Time']);

//     $downtimeCampaigns = Campaign::isDowntime()->get();

//     expect($downtimeCampaigns->contains($downtimeCampaign))->toBeTrue();
//     expect($downtimeCampaigns->contains($normalCampaign))->toBeFalse();
// });
