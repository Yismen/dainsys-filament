<?php

use App\Filament\OperationsDirector\Pages\OperationsDirectorDashboard;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Filament\Facades\Filament;
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

it('shows upcoming birthdays in dashboard widget', function (): void {
    $project = Project::factory()->create();
    $employee1 = Employee::factory()->create([
        'project_id' => $project->id,
        'date_of_birth' => now()->addDays(5)->setYear(1990),
    ]);
    $employee2 = Employee::factory()->create([
        'project_id' => $project->id,
        'date_of_birth' => now()->addDay()->setYear(1985),
    ]);
    $employee3 = Employee::factory()->create([
        'project_id' => $project->id,
        'date_of_birth' => now()->subDay()->setYear(1980),
    ]);
    $employee4 = Employee::factory()->create([
        'project_id' => $project->id,
        'date_of_birth' => now()->addDays(11)->setYear(1992),
    ]);

    $response = $this->get('/operations-director');
    $response->assertOk();
    $response->assertSee($employee1->full_name);
    $response->assertSee($employee2->full_name);
    $response->assertDontSee($employee3->full_name);
    $response->assertDontSee($employee4->full_name);
});
