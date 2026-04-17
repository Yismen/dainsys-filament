<?php

use App\Models\PayrollHour;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\Sanctum;

it('protects the route against unauthorized tokens', function (): void {
    Sanctum::actingAs(User::factory()->create());

    $response = $this->getJson('/api/payroll_hours');

    $response->assertForbidden();
});

it('returns correct structure', function (): void {
    PayrollHour::factory()->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/payroll_hours?date='.now()->format('Y-m-d'));

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

it('date filter is required', function (): void {
    PayrollHour::factory()->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $this->getJson('/api/payroll_hours')
        ->assertJsonValidationErrorFor('date');
});

it('filters by date range', function (): void {
    PayrollHour::factory()->create(['date' => '2026-01-10']);
    PayrollHour::factory()->create(['date' => '2026-01-20']);

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/payroll_hours?date=2026-01-09,2026-01-11');

    expect(count($response->json('data')))->toBe(1);
});

it('filters by fixed date range value using last n days format', function (): void {
    PayrollHour::factory()->create(['date' => now()->subDays(30)->format('Y-m-d')]);
    PayrollHour::factory()->create(['date' => now()->subDays(61)->format('Y-m-d')]);

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/payroll_hours?date=last_45_days');

    expect(count($response->json('data')))->toBe(1);
});

it('filters by fixed date range value using last n months format', function (): void {
    PayrollHour::factory()->create(['date' => now()->subMonth()->format('Y-m-d')]);
    PayrollHour::factory()->create(['date' => now()->subMonths(4)->format('Y-m-d')]);

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/payroll_hours?date=last_2_months');

    expect(count($response->json('data')))->toBe(1);
});

it('filters by week ending at', function (): void {
    PayrollHour::factory()->create(['date' => '2026-01-13']);
    PayrollHour::factory()->create(['date' => '2026-01-20']);

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/payroll_hours?date=2026-01-01,2026-01-31&week_ending_at=2026-01-18');

    expect(count($response->json('data')))->toBe(1);
});

it('filters week ending at by fixed date range value using last n days format', function (): void {
    PayrollHour::factory()->create(['date' => now()->subDays(10)->format('Y-m-d')]);
    PayrollHour::factory()->create(['date' => now()->subDays(80)->format('Y-m-d')]);

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/payroll_hours?date=last_120_days&week_ending_at=last_45_days');

    expect(count($response->json('data')))->toBe(1);
});

it('filters by payroll ending at', function (): void {
    PayrollHour::factory()->create(['date' => '2026-01-10']);
    PayrollHour::factory()->create(['date' => '2026-01-20']);

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/payroll_hours?date=2026-01-01,2026-01-31&payroll_ending_at=2026-01-15');

    expect(count($response->json('data')))->toBe(1);
});

it('filters payroll ending at by fixed date range value using last n days format', function (): void {
    PayrollHour::factory()->create(['date' => now()->subDays(20)->format('Y-m-d')]);
    PayrollHour::factory()->create(['date' => now()->subDays(80)->format('Y-m-d')]);

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/payroll_hours?date=last_120_days&payroll_ending_at=last_45_days');

    expect(count($response->json('data')))->toBe(1);
});

it('returns validation error for invalid date format', function (): void {
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $this->getJson('/api/payroll_hours?date=not-a-date')
        ->assertJsonValidationErrorFor('date');
});

it('returns validation error for invalid fixed date range value', function (): void {
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $this->getJson('/api/payroll_hours?date=last_0_days')
        ->assertJsonValidationErrorFor('date');
});

it('caches payroll hours response and loads from cache on subsequent requests', function (): void {
    Cache::flush();
    PayrollHour::factory()->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    // First request caches the response
    $response1 = $this->getJson('/api/payroll_hours?date='.now()->format('Y-m-d'));
    $response1->assertOk();
    $data1 = $response1->json('data');

    // Second request should load from cache and return same data
    $response2 = $this->getJson('/api/payroll_hours?date='.now()->format('Y-m-d'));
    $response2->assertOk();
    $data2 = $response2->json('data');

    expect($data1)->toBe($data2);
});
