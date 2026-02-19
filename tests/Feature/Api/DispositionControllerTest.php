<?php

use App\Models\Disposition;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('protects the route against unauthorized tokens', function (): void {
    Sanctum::actingAs(User::factory()->create());

    $response = $this->get('/api/dispositions');

    $response->assertForbidden();
});

it('returns correct structure', function (): void {

    Disposition::factory()->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->get('/api/dispositions');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                ],
            ],
        ]);
});
