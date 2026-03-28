<?php

use App\Enums\RevenueTypes;
use App\Filament\OperationsDirector\Resources\Absences\Pages\ListAbsences;
use App\Filament\OperationsDirector\Resources\Clients\Pages\ListClients;
use App\Filament\OperationsDirector\Resources\Deductions\Pages\ListDeductions;
use App\Filament\OperationsDirector\Resources\Downtimes\Pages\ListDowntimes;
use App\Filament\OperationsDirector\Resources\EmployeeMetrics\Pages\ListEmployeeMetrics;
use App\Filament\OperationsDirector\Resources\Employees\Pages\ListEmployees;
use App\Filament\OperationsDirector\Resources\Incentives\Pages\ListIncentives;
use App\Filament\OperationsDirector\Resources\Payrolls\Pages\ListPayrolls;
use App\Filament\OperationsDirector\Resources\Productions\Pages\ListProductions;
use App\Filament\OperationsDirector\Resources\Projects\Pages\ListProjects;
use App\Filament\OperationsDirector\Resources\Sites\Pages\ListSites;
use App\Models\Absence;
use App\Models\Campaign;
use App\Models\Client;
use App\Models\Deduction;
use App\Models\Downtime;
use App\Models\DowntimeReason;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Incentive;
use App\Models\Payroll;
use App\Models\Production;
use App\Models\Project;
use App\Models\Site;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

function createEmployeeForOperationsDirectorProject(Project $project, array $attributes = []): Employee
{
    $employee = Employee::factory()->create(array_merge([
        'project_id' => $project->id,
    ], $attributes));

    Hire::factory()->create([
        'employee_id' => $employee->id,
        'project_id' => $project->id,
        'date' => now()->subDays(14),
    ]);

    return $employee->refresh();
}

function createDowntimeForOperationsDirectorEmployee(Employee $employee, Campaign $campaign): Downtime
{
    return Downtime::factory()->create([
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
        'downtime_reason_id' => DowntimeReason::factory()->create()->id,
        'date' => now()->toDateString(),
        'total_time' => 1,
    ]);
}

beforeEach(function (): void {
    Mail::fake();

    Filament::setCurrentPanel(Filament::getPanel('operations-director'));

    $this->operationsDirector = $this->createSuperAdminUser();
    $this->outsideManager = User::factory()->create();

    actingAs($this->operationsDirector);

    $this->insideProject = Project::factory()->create([
        'manager_id' => $this->operationsDirector->id,
        'name' => 'Inside Project',
    ]);

    $this->outsideProject = Project::factory()->create([
        'manager_id' => $this->outsideManager->id,
        'name' => 'Outside Project',
    ]);

    $this->insideEmployee = createEmployeeForOperationsDirectorProject($this->insideProject, [
        'first_name' => 'Inside',
        'last_name' => 'Employee',
    ]);

    $this->outsideEmployee = createEmployeeForOperationsDirectorProject($this->outsideProject, [
        'first_name' => 'Outside',
        'last_name' => 'Employee',
    ]);
});

it('shows employees without project ownership constraints', function (): void {
    livewire(ListEmployees::class)
        ->assertSee($this->insideEmployee->full_name)
        ->assertSee($this->outsideEmployee->full_name);
});

it('shows projects without manager ownership constraints', function (): void {
    livewire(ListProjects::class)
        ->assertSee('Inside Project')
        ->assertSee('Outside Project');
});

it('shows productions and metrics without manager ownership constraints', function (): void {
    $insideCampaign = Campaign::factory()->create(['project_id' => $this->insideProject->id]);
    $outsideCampaign = Campaign::factory()->create(['project_id' => $this->outsideProject->id]);

    Production::factory()->create([
        'employee_id' => $this->insideEmployee->id,
        'campaign_id' => $insideCampaign->id,
        'date' => now()->toDateString(),
    ]);

    Production::factory()->create([
        'employee_id' => $this->outsideEmployee->id,
        'campaign_id' => $outsideCampaign->id,
        'date' => now()->toDateString(),
    ]);

    livewire(ListProductions::class)
        ->assertSee($this->insideEmployee->full_name)
        ->assertSee($this->outsideEmployee->full_name);

    livewire(ListEmployeeMetrics::class)
        ->loadTable()
        ->assertSee($this->insideEmployee->full_name)
        ->assertSee($this->outsideEmployee->full_name);
});

it('shows payrolls without manager ownership constraints', function (): void {
    Payroll::factory()->create([
        'employee_id' => $this->insideEmployee->id,
        'payable_date' => now()->toDateString(),
        'total_payroll' => 1111,
    ]);

    Payroll::factory()->create([
        'employee_id' => $this->outsideEmployee->id,
        'payable_date' => now()->toDateString(),
        'total_payroll' => 9999,
    ]);

    livewire(ListPayrolls::class)
        ->assertSee($this->insideEmployee->full_name)
        ->assertSee($this->outsideEmployee->full_name);
});

it('shows absences and downtimes without manager ownership constraints', function (): void {
    Absence::factory()->create([
        'employee_id' => $this->insideEmployee->id,
        'created_by' => $this->operationsDirector->id,
        'date' => now()->toDateString(),
    ]);

    Absence::factory()->create([
        'employee_id' => $this->outsideEmployee->id,
        'created_by' => $this->operationsDirector->id,
        'date' => now()->toDateString(),
    ]);

    $insideDowntimeCampaign = Campaign::factory()->create([
        'project_id' => $this->insideProject->id,
        'revenue_type' => RevenueTypes::Downtime,
    ]);

    $outsideDowntimeCampaign = Campaign::factory()->create([
        'project_id' => $this->outsideProject->id,
        'revenue_type' => RevenueTypes::Downtime,
    ]);

    createDowntimeForOperationsDirectorEmployee($this->insideEmployee, $insideDowntimeCampaign);
    createDowntimeForOperationsDirectorEmployee($this->outsideEmployee, $outsideDowntimeCampaign);

    livewire(ListAbsences::class)
        ->assertSee($this->insideEmployee->full_name)
        ->assertSee($this->outsideEmployee->full_name);

    livewire(ListDowntimes::class)
        ->assertSee($this->insideEmployee->full_name)
        ->assertSee($this->outsideEmployee->full_name);
});

it('shows deductions without manager ownership constraints', function (): void {
    Deduction::factory()->create([
        'employee_id' => $this->insideEmployee->id,
        'payable_date' => now()->toDateString(),
    ]);

    Deduction::factory()->create([
        'employee_id' => $this->outsideEmployee->id,
        'payable_date' => now()->toDateString(),
    ]);

    livewire(ListDeductions::class)
        ->assertSee($this->insideEmployee->full_name)
        ->assertSee($this->outsideEmployee->full_name);
});

it('shows sites without constraints', function (): void {
    Site::factory()->create(['name' => 'Inside Site']);
    Site::factory()->create(['name' => 'Outside Site']);

    livewire(ListSites::class)
        ->assertSee('Inside Site')
        ->assertSee('Outside Site');
});

it('shows clients without constraints', function (): void {
    Client::factory()->create(['name' => 'Inside Client']);
    Client::factory()->create(['name' => 'Outside Client']);

    livewire(ListClients::class)
        ->assertSee('Inside Client')
        ->assertSee('Outside Client');
});

it('shows incentives without constraints', function (): void {
    Incentive::factory()->create([
        'employee_id' => $this->insideEmployee->id,
        'project_id' => $this->insideProject->id,
        'payable_date' => now()->toDateString(),
    ]);

    Incentive::factory()->create([
        'employee_id' => $this->outsideEmployee->id,
        'project_id' => $this->outsideProject->id,
        'payable_date' => now()->toDateString(),
    ]);

    livewire(ListIncentives::class)
        ->assertSee($this->insideEmployee->full_name)
        ->assertSee($this->outsideEmployee->full_name);
});
