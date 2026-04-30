<?php

use App\Models\Holiday;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('requires authentication', function (): void {
    $this->getJson('/api/holidays')
        ->assertUnauthorized();
});

it('protects the route against unauthorized tokens', function (): void {
    Sanctum::actingAs(User::factory()->create());

    $response = $this->getJson('/api/holidays');

    $response->assertForbidden();
});

it('returns correct structure', function (): void {
    Holiday::factory()->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/holidays');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'date',
                    'description',
                ],
            ],
        ]);
});

it('returns expected data content', function (): void {
    Holiday::factory()->create([
        'name' => 'New Year',
        'date' => '2026-01-01',
        'description' => 'First day of the year',
    ]);

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/holidays');

    $response->assertOk()
        ->assertJsonPath('data.0.name', 'New Year')
        ->assertJsonPath('data.0.date', '2026-01-01')
        ->assertJsonPath('data.0.description', 'First day of the year');
});

it('filters holidays by year', function (): void {
    Holiday::factory()->create([
        'name' => 'Holiday 2025',
        'date' => '2025-12-25',
    ]);

    Holiday::factory()->create([
        'name' => 'Holiday 2026',
        'date' => '2026-01-01',
    ]);

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/holidays?year=2026');

    $response->assertOk();

    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.name'))->toBe('Holiday 2026');
    expect($response->json('data.0.date'))->toBe('2026-01-01');
});

it('returns validation error for invalid year format', function (): void {
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $this->getJson('/api/holidays?year=invalid')
        ->assertJsonValidationErrorFor('year');
});
