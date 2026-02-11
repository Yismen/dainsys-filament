<?php

use App\Models\Campaign;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('protects the route against unauthorized tokens', function (): void {
    Sanctum::actingAs(User::factory()->create());

    $response = $this->get('/api/campaigns');

    $response->assertForbidden();
});

it('returns correct structure', function (): void {

    Campaign::factory()->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->get('/api/campaigns');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'project_id',
                    'project',
                    'source_id',
                    'source',
                    'revenue_type',
                    'sph_goal',
                    'rate',
                ],
            ],
        ]);
});
