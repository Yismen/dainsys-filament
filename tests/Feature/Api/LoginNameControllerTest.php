<?php

use App\Models\LoginName;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('protects the route against unauthorized tokens', function (): void {
    Sanctum::actingAs(User::factory()->create());

    $response = $this->get('/api/login_names');

    $response->assertForbidden();
});

it('returns correct structure', function (): void {

    LoginName::factory()->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->get('/api/login_names');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'login_name',
                    'employee_id',
                    'employee_full_name',
                ],
            ],
        ]);
});
