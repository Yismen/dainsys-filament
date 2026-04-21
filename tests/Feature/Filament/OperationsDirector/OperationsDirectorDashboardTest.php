<?php

use App\Events\EmployeeHiredEvent;
use App\Filament\OperationsDirector\Pages\OperationsDirectorDashboard;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

function createOperationsDirectorDashboardUser(): User
{
    $role = Role::firstOrCreate(['name' => 'Operations Director Manager', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createOperationsDirectorActiveEmployee(Project $project, array $attributes = []): Employee
{
    $employee = Employee::factory()->create(array_merge([
        'project_id' => $project->id,
    ], $attributes));

    Hire::factory()->create([
        'employee_id' => $employee->id,
        'project_id' => $project->id,
        'date' => now()->subDays(10),
    ]);

    return $employee->refresh();
}

beforeEach(function (): void {
    Mail::fake();

    Event::fake([
        EmployeeHiredEvent::class,
    ]);

    Filament::setCurrentPanel(
        Filament::getPanel('operations-director'),
    );

    $user = createOperationsDirectorDashboardUser();

    actingAs($user);
});

it('renders operations director dashboard with filters and widgets', function (): void {
    livewire(OperationsDirectorDashboard::class)
        ->assertSuccessful()
        ->assertSee('Projects')
        ->assertSee('Clients')
        ->assertSeeHtml('OperationsDirectorStatsOverview')
        ->assertSeeHtml('OperationsDirectorQAStatsWidget')
        ->assertSeeHtml('EmployeesByProjectChart')
        ->assertSeeHtml('DailyRevenueByProjectChart')
        ->assertSeeHtml('DailyEfficiencyByProjectChart')
        ->assertSeeHtml('DailySphPercentageByProjectChart')
        ->assertSeeHtml('UpcomingBirthdaysTable')
        ->assertSeeHtml('AbsencesByEmployeeTable');
});
