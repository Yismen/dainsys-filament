<?php

use App\Enums\EmployeeStatuses;
use App\Enums\HRActivityTypes;
use App\Events\EmployeeHiredEvent;
use App\Events\HRActivityRequestCreated;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    Mail::fake();
    Event::fake([
        EmployeeHiredEvent::class,
        HRActivityRequestCreated::class,
    ]); // Only fake events that cause issues
});

it('displays employees assigned to supervisor in query', function (): void {
    /** @var User $user */
    $user = User::factory()->create();
    $supervisor = Supervisor::factory()->create([
        'user_id' => $user->id,
    ]);

    $assignedEmployee = Employee::factory()->create();
    Hire::factory()->create([
        'employee_id' => $assignedEmployee->id,
        'supervisor_id' => $supervisor->id,
    ]);

    $unassignedEmployee = Employee::factory()->create();

    $query = Employee::query()
        ->whereHas('hires', function ($q) use ($supervisor): void {
            $q->where('supervisor_id', $supervisor->id);
        })
        ->whereNotIn('status', [EmployeeStatuses::Terminated]);

    $ids = $query->pluck('id')->toArray();

    expect($ids)->toContain($assignedEmployee->id);
    expect($ids)->not->toContain($unassignedEmployee->id);
});

it('does not show terminated employees in query', function (): void {
    /** @var User $user */
    $user = User::factory()->create();
    $supervisor = Supervisor::factory()->create([
        'user_id' => $user->id,
    ]);

    $activeEmployee = Employee::factory()->create();
    Hire::factory()->create([
        'employee_id' => $activeEmployee->id,
        'supervisor_id' => $supervisor->id,
    ]);

    $terminatedEmployee = Employee::factory()->create();
    Hire::factory()->create([
        'employee_id' => $terminatedEmployee->id,
        'supervisor_id' => $supervisor->id,
        'date' => now()->subDay(),
    ]);
    \App\Models\Termination::factory()->create([
        'employee_id' => $terminatedEmployee->id,
        'date' => now(),
    ]);

    // Manually trigger the status update
    $terminatedEmployee->refresh();
    $terminatedEmployee->save();
    $terminatedEmployee->refresh();

    expect($terminatedEmployee->status)->toBe(EmployeeStatuses::Terminated);

    $query = Employee::query()
        ->whereHas('hires', function ($q) use ($supervisor): void {
            $q->where('supervisor_id', $supervisor->id);
        })
        ->whereNotIn('status', [EmployeeStatuses::Terminated]);

    expect($query->count())->toBe(1);
    expect($query->pluck('id'))->toContain($activeEmployee->id);
    expect($query->pluck('id'))->not->toContain($terminatedEmployee->id);
});

it('allows requesting hr activity for employee', function (): void {
    /** @var User $user */
    $user = User::factory()->create();
    $supervisor = Supervisor::factory()->create([
        'user_id' => $user->id,
    ]);

    $employee = Employee::factory()->create();
    Hire::factory()->create([
        'employee_id' => $employee->id,
        'supervisor_id' => $supervisor->id,
    ]);

    actingAs($user);

    $activityRequest = \App\Models\HRActivityRequest::create([
        'employee_id' => $employee->id,
        'supervisor_id' => $supervisor->id,
        'activity_type' => HRActivityTypes::Vacations,
        'description' => 'Employee requesting vacation time',
        'requested_at' => now(),
    ]);

    $this->assertDatabaseHas('h_r_activity_requests', [
        'id' => $activityRequest->id,
        'employee_id' => $employee->id,
        'supervisor_id' => $supervisor->id,
        'activity_type' => HRActivityTypes::Vacations->value,
    ]);
});
