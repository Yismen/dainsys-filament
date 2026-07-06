<?php

use App\Filament\ProjectExecutive\Widgets\AbsencesByEmployeeTable;
use App\Filament\ProjectExecutive\Widgets\EmployeesByProjectChart;
use App\Filament\ProjectExecutive\Widgets\MonthlyRevenueByProjectChart;
use App\Filament\ProjectExecutive\Widgets\ProjectExecutiveStatsOverview;
use App\Filament\ProjectExecutive\Widgets\UpcomingBirthdaysTable;
use App\Filament\ProjectExecutive\Widgets\WeeklyEfficiencyByProjectChart;
use App\Filament\ProjectExecutive\Widgets\WeeklyRevenueByProjectChart;
use App\Filament\ProjectExecutive\Widgets\WeeklySphPercentageByProjectChart;
use App\Models\Absence;
use App\Models\Campaign;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Production;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    Mail::fake();

    Filament::setCurrentPanel(
        Filament::getPanel('project-executive'),
    );
});

function createProjectExecutiveWidgetUser(): User
{
    $role = Role::firstOrCreate(['name' => 'Project Executive Manager', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createActiveEmployee(Project $project, array $attributes = []): Employee
{
    $employee = Employee::factory()->create(array_merge([
        'project_id' => $project->id,
        'date_of_birth' => now()->subYears(25)->toDateString(),
    ], $attributes));

    Hire::factory()->create([
        'employee_id' => $employee->id,
        'project_id' => $project->id,
        'date' => now()->subDays(15),
    ]);

    $employee->refresh();

    return $employee;
}

function createProductionRecord(Employee $employee, Campaign $campaign, $date, array $metrics): Production
{
    $production = Production::factory()->create([
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
        'date' => $date->toDateString(),
        'total_time' => 1,
        'production_time' => 1,
        'conversions' => 1,
    ]);

    $production->forceFill($metrics)->saveQuietly();

    return $production;
}

it('shows total active employees assigned to manager projects', function (): void {
    $managerUser = createProjectExecutiveWidgetUser();
    actingAs($managerUser);

    $project = Project::factory()->create(['manager_id' => $managerUser->id]);
    $otherManager = User::factory()->create();
    $otherProject = Project::factory()->create(['manager_id' => $otherManager->id]);

    createActiveEmployee($project);
    createActiveEmployee($project);
    createActiveEmployee($otherProject);

    Livewire::test(ProjectExecutiveStatsOverview::class)
        ->assertSee('Assigned active employees')
        ->assertSee('2');
});

it('renders employees by project chart scoped to current manager', function (): void {
    $managerUser = createProjectExecutiveWidgetUser();
    actingAs($managerUser);

    $projectA = Project::factory()->create(['manager_id' => $managerUser->id, 'name' => 'Project A']);
    $projectB = Project::factory()->create(['manager_id' => $managerUser->id, 'name' => 'Project B']);

    $otherManager = User::factory()->create();
    $foreignProject = Project::factory()->create(['manager_id' => $otherManager->id, 'name' => 'Project X']);

    createActiveEmployee($projectA);
    createActiveEmployee($projectA);
    createActiveEmployee($projectB);
    createActiveEmployee($foreignProject);

    $data = Livewire::test(EmployeesByProjectChart::class)->instance()->getData();

    expect($data['labels'])->toContain('Project A', 'Project B')
        ->and($data['labels'])->not->toContain('Project X')
        ->and(array_sum($data['datasets'][0]['data']))->toBe(3);
});

it('builds the weekly revenue dataset for manager projects only', function (): void {
    $managerUser = createProjectExecutiveWidgetUser();
    actingAs($managerUser);

    $projectA = Project::factory()->create(['manager_id' => $managerUser->id, 'name' => 'Project A']);
    $projectB = Project::factory()->create(['manager_id' => $managerUser->id, 'name' => 'Project B']);
    $foreignManager = User::factory()->create();
    $foreignProject = Project::factory()->create(['manager_id' => $foreignManager->id, 'name' => 'Project X']);

    $employeeA = createActiveEmployee($projectA);
    $employeeA2 = createActiveEmployee($projectA);
    $employeeB = createActiveEmployee($projectB);
    $foreignEmployee = createActiveEmployee($foreignProject);

    $campaignA = Campaign::factory()->create(['project_id' => $projectA->id]);
    $campaignB = Campaign::factory()->create(['project_id' => $projectB->id]);
    $campaignX = Campaign::factory()->create(['project_id' => $foreignProject->id]);

    $today = now()->startOfDay();

    createProductionRecord($employeeA, $campaignA, $today, ['revenue' => 100]);
    createProductionRecord($employeeA2, $campaignA, $today, ['revenue' => 50]);
    createProductionRecord($employeeB, $campaignB, $today, ['revenue' => 70]);
    createProductionRecord($foreignEmployee, $campaignX, $today, ['revenue' => 999]);

    $data = Livewire::test(WeeklyRevenueByProjectChart::class)->instance()->getData();

    $datasets = collect($data['datasets'])->keyBy('label');
    $weekLabel = now()->startOfWeek()->format('M d').' - '.now()->endOfWeek()->format('M d');
    $weekIndex = array_search($weekLabel, $data['labels'], true);

    expect($datasets->has('Project A'))->toBeTrue()
        ->and($datasets->has('Project B'))->toBeTrue()
        ->and($datasets->has('Project X'))->toBeFalse()
        ->and($weekIndex)->not->toBeFalse()
        ->and($datasets['Project A']['data'][$weekIndex])->toBe(150.0)
        ->and($datasets['Project B']['data'][$weekIndex])->toBe(70.0);
});

it('builds the monthly revenue dataset for manager projects only', function (): void {
    $managerUser = createProjectExecutiveWidgetUser();
    actingAs($managerUser);

    $projectA = Project::factory()->create(['manager_id' => $managerUser->id, 'name' => 'Project A']);
    $projectB = Project::factory()->create(['manager_id' => $managerUser->id, 'name' => 'Project B']);
    $foreignManager = User::factory()->create();
    $foreignProject = Project::factory()->create(['manager_id' => $foreignManager->id, 'name' => 'Project X']);

    $employeeA = createActiveEmployee($projectA);
    $employeeA2 = createActiveEmployee($projectA);
    $employeeB = createActiveEmployee($projectB);
    $foreignEmployee = createActiveEmployee($foreignProject);

    $campaignA = Campaign::factory()->create(['project_id' => $projectA->id]);
    $campaignB = Campaign::factory()->create(['project_id' => $projectB->id]);
    $campaignX = Campaign::factory()->create(['project_id' => $foreignProject->id]);

    $currentMonth = now()->startOfMonth();

    createProductionRecord($employeeA, $campaignA, $currentMonth->copy()->addDays(1), ['revenue' => 100]);
    createProductionRecord($employeeA2, $campaignA, $currentMonth->copy()->addDays(2), ['revenue' => 50]);
    createProductionRecord($employeeB, $campaignB, $currentMonth->copy()->addDays(3), ['revenue' => 70]);
    createProductionRecord($foreignEmployee, $campaignX, $currentMonth->copy()->addDays(4), ['revenue' => 999]);

    $data = Livewire::test(MonthlyRevenueByProjectChart::class)->instance()->getData();

    $datasets = collect($data['datasets'])->keyBy('label');
    $currentMonthLabel = now()->format('M Y');
    $currentMonthIndex = array_search($currentMonthLabel, $data['labels'], true);

    expect($data['labels'])->toHaveCount(6)
        ->and($datasets->has('Project A'))->toBeTrue()
        ->and($datasets->has('Project B'))->toBeTrue()
        ->and($datasets->has('Project X'))->toBeFalse()
        ->and($currentMonthIndex)->not->toBeFalse()
        ->and($datasets['Project A']['data'][$currentMonthIndex])->toBe(150.0)
        ->and($datasets['Project B']['data'][$currentMonthIndex])->toBe(70.0);
});

it('builds the weekly efficiency dataset for manager projects only', function (): void {
    $managerUser = createProjectExecutiveWidgetUser();
    actingAs($managerUser);

    $project = Project::factory()->create(['manager_id' => $managerUser->id, 'name' => 'Project Efficiency']);
    $foreignManager = User::factory()->create();
    $foreignProject = Project::factory()->create(['manager_id' => $foreignManager->id, 'name' => 'Project Foreign']);

    $employee = createActiveEmployee($project);
    $foreignEmployee = createActiveEmployee($foreignProject);

    $campaign = Campaign::factory()->create(['project_id' => $project->id]);
    $foreignCampaign = Campaign::factory()->create(['project_id' => $foreignProject->id]);

    $today = now()->startOfDay();

    createProductionRecord($employee, $campaign, $today, [
        'production_time' => 6,
        'total_time' => 8,
        'conversions' => 12,
        'conversions_goal' => 10,
    ]);

    createProductionRecord($foreignEmployee, $foreignCampaign, $today, [
        'production_time' => 1,
        'total_time' => 1,
        'conversions' => 1,
        'conversions_goal' => 1,
    ]);

    $data = Livewire::test(WeeklyEfficiencyByProjectChart::class)->instance()->getData();
    $datasets = collect($data['datasets'])->keyBy('label');

    $weekLabel = now()->startOfWeek()->format('M d').' - '.now()->endOfWeek()->format('M d');
    $weekIndex = array_search($weekLabel, $data['labels'], true);

    expect($datasets->has('Project Efficiency'))->toBeTrue()
        ->and($datasets->has('Project Foreign'))->toBeFalse()
        ->and($weekIndex)->not->toBeFalse()
        ->and($datasets['Project Efficiency']['data'][$weekIndex])->toBe(75.0);
});

it('builds the weekly sph percentage dataset for manager projects only', function (): void {
    $managerUser = createProjectExecutiveWidgetUser();
    actingAs($managerUser);

    $project = Project::factory()->create(['manager_id' => $managerUser->id, 'name' => 'Project SPH']);
    $foreignManager = User::factory()->create();
    $foreignProject = Project::factory()->create(['manager_id' => $foreignManager->id, 'name' => 'Project Foreign']);

    $employee = createActiveEmployee($project);
    $foreignEmployee = createActiveEmployee($foreignProject);

    $campaign = Campaign::factory()->create(['project_id' => $project->id]);
    $foreignCampaign = Campaign::factory()->create(['project_id' => $foreignProject->id]);

    $today = now()->startOfDay();

    createProductionRecord($employee, $campaign, $today, [
        'conversions' => 40,
        'production_time' => 10,
        'conversions_goal' => 20,
    ]);

    createProductionRecord($foreignEmployee, $foreignCampaign, $today, [
        'conversions' => 10,
        'production_time' => 5,
        'conversions_goal' => 10,
    ]);

    $data = Livewire::test(WeeklySphPercentageByProjectChart::class)->instance()->getData();
    $datasets = collect($data['datasets'])->keyBy('label');

    $weekLabel = now()->startOfWeek()->format('M d').' - '.now()->endOfWeek()->format('M d');
    $weekIndex = array_search($weekLabel, $data['labels'], true);

    expect($datasets->has('Project SPH'))->toBeTrue()
        ->and($datasets->has('Project Foreign'))->toBeFalse()
        ->and($weekIndex)->not->toBeFalse()
        ->and($datasets['Project SPH']['data'][$weekIndex])->toBe(200.0);
});

it('shows birthdays and absences for employees in manager projects only', function (): void {
    $managerUser = createProjectExecutiveWidgetUser();
    actingAs($managerUser);

    $project = Project::factory()->create(['manager_id' => $managerUser->id]);
    $foreignManager = User::factory()->create();
    $foreignProject = Project::factory()->create(['manager_id' => $foreignManager->id]);

    $insideEmployee = createActiveEmployee($project, [
        'date_of_birth' => now()->addDays(5)->toDateString(),
    ]);

    $outsideEmployee = createActiveEmployee($foreignProject, [
        'date_of_birth' => now()->addDays(5)->toDateString(),
    ]);

    Absence::factory()->create([
        'employee_id' => $insideEmployee->id,
        'created_by' => $managerUser->id,
        'date' => now()->subDays(3)->toDateString(),
    ]);

    Absence::factory()->create([
        'employee_id' => $outsideEmployee->id,
        'created_by' => $managerUser->id,
        'date' => now()->subDays(3)->toDateString(),
    ]);

    Livewire::test(UpcomingBirthdaysTable::class)
        ->loadTable()
        ->assertCanSeeTableRecords([$insideEmployee])
        ->assertCanNotSeeTableRecords([$outsideEmployee]);

    Livewire::test(AbsencesByEmployeeTable::class)
        ->loadTable()
        ->assertCanSeeTableRecords([$insideEmployee])
        ->assertCanNotSeeTableRecords([$outsideEmployee]);
});
