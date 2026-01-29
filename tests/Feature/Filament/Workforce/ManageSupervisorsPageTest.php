<?php

use App\Filament\Workforce\Pages\ManageSupervisors;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Supervisor;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('workforce'),
    );
});

it('renders supervisors with their employees', function (): void {
    $supervisor = Supervisor::factory()->create(['is_active' => true]);
    $employee = Employee::factory()->create(['supervisor_id' => $supervisor->id]);

    /** @var User $user */
    $user = User::factory()->createOne();
    $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user->assignRole($role);

    actingAs($user);

    livewire(ManageSupervisors::class)
        ->assertSee($supervisor->name);
});
