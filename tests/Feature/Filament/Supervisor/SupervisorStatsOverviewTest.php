<?php

use App\Enums\EmployeeStatuses;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Supervisor;
use App\Models\Suspension;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('supervisor'),
    );
});

it('shows counts for assigned employees', function (): void {
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

    $anotherActiveEmployee = Employee::factory()->create();
    Hire::factory()->create([
        'employee_id' => $anotherActiveEmployee->id,
        'supervisor_id' => $supervisor->id,
    ]);

    $suspendedEmployee = Employee::factory()->create();
    Hire::factory()->create([
        'employee_id' => $suspendedEmployee->id,
        'supervisor_id' => $supervisor->id,
    ]);
    Suspension::factory()->create([
        'employee_id' => $suspendedEmployee->id,
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addDay(),
    ]);

    $suspendedEmployee->refresh();
    expect($suspendedEmployee->status)->toBe(EmployeeStatuses::Suspended);

    actingAs($user);

    $response = get(route('filament.supervisor.pages.dashboard'));

    $response->assertOk();
    $response->assertSee('Total Employees');
    $response->assertSee('3');
    $response->assertSee('Active Employees');
    $response->assertSee('2');
    $response->assertSee('Suspended Employees');
    $response->assertSee('1');
});
