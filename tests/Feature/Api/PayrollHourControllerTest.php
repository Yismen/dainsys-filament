<?php

use App\Models\PayrollHour;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('protects the route against unauthorized tokens', function (): void {
    Sanctum::actingAs(User::factory()->create());

    $response = $this->getJson('/api/payroll_hours');

    $response->assertForbidden();
});

it('returns correct structure', function (): void {
    PayrollHour::factory()->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/payroll_hours');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'employee_id',
                    'employee_full_name',
                    'date',
                    'total_hours',
                    'regular_hours',
                    'overtime_hours',
                    'holiday_hours',
                    'seventh_day_hours',
                    'week_ending_at',
                    'payroll_ending_at',
                    'is_sunday',
                    'is_holiday',
                ],
            ],
        ]);
});

it('filters by date range', function (): void {
    PayrollHour::factory()->create(['date' => '2026-01-10']);
    PayrollHour::factory()->create(['date' => '2026-01-20']);

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/payroll_hours?date=2026-01-09,2026-01-11');

    expect(count($response->json('data')))->toBe(1);
});

it('filters by week ending at', function (): void {
    PayrollHour::factory()->create(['date' => '2026-01-13']);
    PayrollHour::factory()->create(['date' => '2026-01-20']);

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/payroll_hours?week_ending_at=2026-01-18');

    expect(count($response->json('data')))->toBe(1);
});

it('filters by payroll ending at', function (): void {
    PayrollHour::factory()->create(['date' => '2026-01-10']);
    PayrollHour::factory()->create(['date' => '2026-01-20']);

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/payroll_hours?payroll_ending_at=2026-01-15');

    expect(count($response->json('data')))->toBe(1);
});

it('returns validation error for invalid date format', function (): void {
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $this->getJson('/api/payroll_hours?date=2026/01/10')
        ->assertJsonValidationErrorFor('date');
});
