<?php

use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('project must belong to a client', function (): void {
    $client = Client::factory()->create();
    $project = Project::factory()->create(['client_id' => $client->id]);
    expect($project->client)->toBeInstanceOf(Client::class);
    expect($project->client())->toBeInstanceOf(BelongsTo::class);
});

test('client has multiple projects', function (): void {
    $client = Client::factory()->create();
    Project::factory()->count(2)->create(['client_id' => $client->id]);
    $client->refresh();
    expect($client->projects)->toHaveCount(2);
    expect($client->projects())->toBeInstanceOf(HasMany::class);
});
