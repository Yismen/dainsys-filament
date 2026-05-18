<?php

use App\Models\Employee;
use App\Models\Hire;
use App\Models\Production;
use App\Models\Termination;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

beforeEach(fn () => Mail::fake());

it('protects the route against unauthorized tokens', function (): void {
    Sanctum::actingAs(User::factory()->create());

    $response = getJson('/api/employees');

    $response->assertForbidden();
});

it('returns correct structure', function (): void {

    Production::factory()->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = getJson('/api/employees');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'full_name',
                    'personal_id_type',
                    'personal_id',
                    'internal_id',
                    'hired_at',
                    'site_id',
                    'site',
                    'project_id',
                    'project',
                    'department_id',
                    'department',
                    'supervisor_id',
                    'supervisor',
                    'position_id',
                    'position',
                    'salary_type',
                    'hourly_rate',
                    'status',
                    'bank_name',
                    'bank_account_number',
                    'is_universal',
                ],
            ],
        ]);
});

// filters data by status
it('filters by status', function (): void {

    Employee::factory()->create();
    Employee::factory()->has(Hire::factory())->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = getJson('/api/employees' . '?status=Hired');

    expect(count($response->json()['data']))
        ->toBe(1);
});

// filters data by status
it('filters by status Hired when active is passed', function (): void {

    Employee::factory()->create();
    Employee::factory()->has(Hire::factory())->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = getJson('/api/employees'.'?status=active')
        ->assertJsonCount(1);

    expect(count($response->json()['data']))
        ->toBe(1);
});

// filters data by status
it('filters by status Terminated when inactive is passed', function (): void {

    Employee::factory()->create();
    Employee::factory()->has(Hire::factory())->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = getJson('/api/employees'.'?status=active')
        ->assertJsonCount(1);

    expect(count($response->json()['data']))
        ->toBe(1);
});

// filters data by status
it('filters by status for recently terminated employees', function (): void {

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    Employee::factory()->create();
    $employeeRecentlyTerminated = Employee::factory()->has(Hire::factory(['date' => now()->subDays(100)]))->create();
    $employeeTerminatedLongAgo = Employee::factory()->has(Hire::factory(['date' => now()->subDays(100)]))->create();

    Termination::factory()->create([
        'employee_id' => $employeeRecentlyTerminated->id,
        'date' => now()->subDays(10),
    ]);

    Termination::factory()->create([
        'employee_id' => $employeeTerminatedLongAgo->id,
        'date' => now()->subDays(90),
    ]);

    $response = getJson('/api/employees'.'?status=recents')
        ->assertJsonCount(2, 'data');

    expect(count($response->json()['data']))
        ->toBe(2);
});
