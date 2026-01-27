<?php

use App\Enums\DowntimeStatuses;
use App\Filament\Supervisor\Resources\Downtimes\DowntimeResource;
use App\Models\Downtime;
use App\Models\Employee;
use App\Models\Supervisor;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('supervisor can only see downtimes for their employees', function (): void {
    $user = User::factory()->create();
    $supervisor = Supervisor::factory()->create(['user_id' => $user->id]);

    $myEmployee = Employee::factory()->hired()->create(['supervisor_id' => $supervisor->id]);
    $otherEmployee = Employee::factory()->hired()->create();

    $myDowntime = Downtime::factory()->create(['employee_id' => $myEmployee->id]);
    $otherDowntime = Downtime::factory()->create(['employee_id' => $otherEmployee->id]);

    actingAs($user);

    $query = DowntimeResource::getEloquentQuery();

    expect($query->pluck('id'))->toContain($myDowntime->id);
    expect($query->pluck('id'))->not->toContain($otherDowntime->id);
});

it('supervisor without supervisor record sees no downtimes', function (): void {
    $user = User::factory()->create();
    // No supervisor record for this user

    $employee = Employee::factory()->hired()->create();
    $downtime = Downtime::factory()->create(['employee_id' => $employee->id]);

    actingAs($user);

    $query = DowntimeResource::getEloquentQuery();

    expect($query->count())->toBe(0);
});

it('downtime status defaults to pending when created', function (): void {
    $employee = Employee::factory()->hired()->create();
    $downtime = Downtime::factory()->create(['employee_id' => $employee->id]);

    expect($downtime->status)->toBe(DowntimeStatuses::Pending);
});

it('downtime can be created with specific status', function (): void {
    $employee = Employee::factory()->hired()->create();

    $pendingDowntime = Downtime::factory()->create([
        'employee_id' => $employee->id,
        'status' => DowntimeStatuses::Pending,
    ]);

    $approvedDowntime = Downtime::factory()->create([
        'employee_id' => $employee->id,
        'status' => DowntimeStatuses::Approved,
    ]);

    expect($pendingDowntime->status)->toBe(DowntimeStatuses::Pending);
    expect($approvedDowntime->status)->toBe(DowntimeStatuses::Approved);
});

it('downtime status changes to approved when approved', function (): void {
    $user = User::factory()->create();
    $employee = Employee::factory()->hired()->create();
    $downtime = Downtime::factory()->create([
        'employee_id' => $employee->id,
        'status' => DowntimeStatuses::Pending,
    ]);

    actingAs($user);

    expect($downtime->status)->toBe(DowntimeStatuses::Pending);

    $downtime->aprove();

    expect($downtime->fresh()->status)->toBe(DowntimeStatuses::Approved);
    expect($downtime->fresh()->aprover_id)->toBe($user->id);
});
