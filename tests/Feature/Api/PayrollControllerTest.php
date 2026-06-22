<?php

use App\Models\Payroll;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('protects the route against unauthorized tokens', function (): void {
    Sanctum::actingAs(User::factory()->create());

    $response = $this->getJson('/api/payrolls');

    $response->assertForbidden();
});

it('returns correct structure', function (): void {
    Payroll::factory()->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/payrolls?payable_date='.now()->format('Y-m-d'));

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'employee_id',
                    'employee_full_name',
                    'payable_date',
                    'total_hours',
                    'gross_income',
                    'nightly_incomes',
                    'overtime_incomes',
                    'holiday_incomes',
                    'additional_incentives_2',
                    'additional_incentives_1',
                ],
            ],
        ]);
});

it('date or payable_date filter is required', function (): void {
    Payroll::factory()->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $this->getJson('/api/payrolls')
        ->assertJsonValidationErrorFor('date')
        ->assertJsonValidationErrorFor('payable_date');
});

it('accepts payable_date in place of date', function (): void {
    Payroll::factory()->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $this->getJson('/api/payrolls?payable_date='.now()->format('Y-m-d'))
        ->assertOk();
});

it('filters by date range', function (): void {
    Payroll::factory()->create(['payable_date' => '2026-01-10']);
    Payroll::factory()->create(['payable_date' => '2026-01-20']);

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/payrolls?date=2026-01-09,2026-01-11');

    expect(count($response->json('data')))->toBe(1);
});

it('filters by fixed date range value using last n days format', function (): void {
    Payroll::factory()->create(['payable_date' => now()->subDays(30)->format('Y-m-d')]);
    Payroll::factory()->create(['payable_date' => now()->subDays(61)->format('Y-m-d')]);

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/payrolls?date=last_45_days');

    expect(count($response->json('data')))->toBe(1);
});

it('filters by fixed date range value using last n months format', function (): void {
    Payroll::factory()->create(['payable_date' => now()->subMonth()->format('Y-m-d')]);
    Payroll::factory()->create(['payable_date' => now()->subMonths(4)->format('Y-m-d')]);

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/payrolls?payable_date=last_2_months');

    expect(count($response->json('data')))->toBe(1);
});

it('returns validation error for invalid date format', function (): void {
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $this->getJson('/api/payrolls?date=not-a-date')
        ->assertJsonValidationErrorFor('date');
});

it('returns validation error for invalid fixed date range value', function (): void {
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $this->getJson('/api/payrolls?date=last_0_days')
        ->assertJsonValidationErrorFor('date');
});
