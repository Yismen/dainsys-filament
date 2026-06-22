<?php

use App\Models\Incentive;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('protects the route against unauthorized tokens', function (): void {
    Sanctum::actingAs(User::factory()->create());

    $response = $this->getJson('/api/incentives');

    $response->assertForbidden();
});

it('returns correct structure', function (): void {
    Incentive::factory()->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/incentives?payable_date='.now()->format('Y-m-d'));

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'employee_id',
                    'employee_full_name',
                    'project_id',
                    'project_name',
                    'payable_date',
                    'amount',
                    'total_production_hours',
                    'total_sales',
                ],
            ],
        ]);
});

it('date or payable_date filter is required', function (): void {
    Incentive::factory()->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $this->getJson('/api/incentives')
        ->assertJsonValidationErrorFor('date')
        ->assertJsonValidationErrorFor('payable_date');
});

it('accepts payable_date in place of date', function (): void {
    Incentive::factory()->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $this->getJson('/api/incentives?payable_date='.now()->format('Y-m-d'))
        ->assertOk();
});

it('filters by date range', function (): void {
    Incentive::factory()->create(['payable_date' => '2026-01-10']);
    Incentive::factory()->create(['payable_date' => '2026-01-20']);

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/incentives?date=2026-01-09,2026-01-11');

    expect(count($response->json('data')))->toBe(1);
});

it('filters by fixed date range value using last n days format', function (): void {
    Incentive::factory()->create(['payable_date' => now()->subDays(30)->format('Y-m-d')]);
    Incentive::factory()->create(['payable_date' => now()->subDays(61)->format('Y-m-d')]);

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/incentives?date=last_45_days');

    expect(count($response->json('data')))->toBe(1);
});

it('filters by fixed date range value using last n months format', function (): void {
    Incentive::factory()->create(['payable_date' => now()->subMonth()->format('Y-m-d')]);
    Incentive::factory()->create(['payable_date' => now()->subMonths(4)->format('Y-m-d')]);

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/incentives?payable_date=last_2_months');

    expect(count($response->json('data')))->toBe(1);
});

it('returns validation error for invalid date format', function (): void {
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $this->getJson('/api/incentives?date=not-a-date')
        ->assertJsonValidationErrorFor('date');
});

it('returns validation error for invalid fixed date range value', function (): void {
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $this->getJson('/api/incentives?date=last_0_days')
        ->assertJsonValidationErrorFor('date');
});
