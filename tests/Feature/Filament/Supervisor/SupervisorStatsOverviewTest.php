<?php

use App\Enums\EmployeeStatuses;
use App\Events\EmployeeHiredEvent;
use App\Filament\Supervisor\Widgets\SupervisorStatsOverview;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Supervisor;
use App\Models\Suspension;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    Mail::fake();
    Event::fake([EmployeeHiredEvent::class]);

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
        'date' => now()->subDays(10),
    ]);
    Suspension::factory()->create([
        'employee_id' => $suspendedEmployee->id,
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addDay(),
    ]);

    $suspendedEmployee->refresh();
    expect($suspendedEmployee->status)->toBe(EmployeeStatuses::Suspended);

    actingAs($user);

    Livewire::test(SupervisorStatsOverview::class)
        ->assertSee('Total Employees')
        ->assertSee('3')
        ->assertSee('Active Employees')
        ->assertSee('2')
        ->assertSee('Suspended Employees')
        ->assertSee('1');
});
