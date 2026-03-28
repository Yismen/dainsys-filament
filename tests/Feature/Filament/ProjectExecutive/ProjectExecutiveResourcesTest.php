<?php

use App\Enums\RevenueTypes;
use App\Filament\ProjectExecutive\Resources\Absences\Pages\ListAbsences;
use App\Filament\ProjectExecutive\Resources\Deductions\Pages\ListDeductions;
use App\Filament\ProjectExecutive\Resources\Downtimes\Pages\ListDowntimes;
use App\Filament\ProjectExecutive\Resources\EmployeeMetrics\Pages\ListEmployeeMetrics;
use App\Filament\ProjectExecutive\Resources\Employees\Pages\ListEmployees;
use App\Filament\ProjectExecutive\Resources\Payrolls\Pages\ListPayrolls;
use App\Filament\ProjectExecutive\Resources\Productions\Pages\ListProductions;
use App\Filament\ProjectExecutive\Resources\Projects\Pages\ListProjects;
use App\Models\Absence;
use App\Models\Campaign;
use App\Models\Deduction;
use App\Models\Downtime;
use App\Models\DowntimeReason;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Payroll;
use App\Models\Production;
use App\Models\Project;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

function createEmployeeForProject(Project $project, array $attributes = []): Employee
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

function createDowntimeForEmployee(Employee $employee, Campaign $campaign): Downtime
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

    Filament::setCurrentPanel(Filament::getPanel('project-executive'));

    $this->projectExecutive = $this->createSuperAdminUser();
    $this->outsideManager = User::factory()->create();

    actingAs($this->projectExecutive);

    $this->insideProject = Project::factory()->create([
        'manager_id' => $this->projectExecutive->id,
        'name' => 'Inside Project',
    ]);

    $this->outsideProject = Project::factory()->create([
        'manager_id' => $this->outsideManager->id,
        'name' => 'Outside Project',
    ]);

    $this->insideEmployee = createEmployeeForProject($this->insideProject, [
        'first_name' => 'Inside',
        'last_name' => 'Employee',
    ]);

    $this->outsideEmployee = createEmployeeForProject($this->outsideProject, [
        'first_name' => 'Outside',
        'last_name' => 'Employee',
    ]);
});

it('shows only employees from the executive projects', function (): void {
    livewire(ListEmployees::class)
        ->assertSee($this->insideEmployee->full_name)
        ->assertDontSee($this->outsideEmployee->full_name);
});

it('shows only projects from the executive ownership scope', function (): void {
    livewire(ListProjects::class)
        ->assertSee('Inside Project')
        ->assertDontSee('Outside Project');
});

it('shows productions and metrics scoped to the executive projects', function (): void {
    $insideCampaign = Campaign::factory()->create(['project_id' => $this->insideProject->id]);
    $outsideCampaign = Campaign::factory()->create(['project_id' => $this->outsideProject->id]);

    $insideProduction = Production::factory()->create([
        'employee_id' => $this->insideEmployee->id,
        'campaign_id' => $insideCampaign->id,
        'date' => now()->toDateString(),
    ]);

    $outsideProduction = Production::factory()->create([
        'employee_id' => $this->outsideEmployee->id,
        'campaign_id' => $outsideCampaign->id,
        'date' => now()->toDateString(),
    ]);

    livewire(ListProductions::class)
        ->assertSee($this->insideEmployee->full_name)
        ->assertDontSee($this->outsideEmployee->full_name);

    livewire(ListEmployeeMetrics::class)
        ->loadTable()
        ->assertSee($this->insideEmployee->full_name)
        ->assertDontSee($this->outsideEmployee->full_name);
});

it('shows payrolls scoped to the executive projects', function (): void {
    $insidePayroll = Payroll::factory()->create([
        'employee_id' => $this->insideEmployee->id,
        'payable_date' => now()->toDateString(),
        'total_payroll' => 1111,
    ]);

    $outsidePayroll = Payroll::factory()->create([
        'employee_id' => $this->outsideEmployee->id,
        'payable_date' => now()->toDateString(),
        'total_payroll' => 9999,
    ]);

    livewire(ListPayrolls::class)
        ->assertSee($this->insideEmployee->full_name)
        ->assertDontSee($this->outsideEmployee->full_name);
});

it('shows absences and downtimes scoped to the executive projects', function (): void {
    $insideAbsence = Absence::factory()->create([
        'employee_id' => $this->insideEmployee->id,
        'created_by' => $this->projectExecutive->id,
        'date' => now()->toDateString(),
    ]);

    $outsideAbsence = Absence::factory()->create([
        'employee_id' => $this->outsideEmployee->id,
        'created_by' => $this->projectExecutive->id,
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

    $insideDowntime = createDowntimeForEmployee($this->insideEmployee, $insideDowntimeCampaign);
    $outsideDowntime = createDowntimeForEmployee($this->outsideEmployee, $outsideDowntimeCampaign);

    livewire(ListAbsences::class)
        ->assertSee($this->insideEmployee->full_name)
        ->assertDontSee($this->outsideEmployee->full_name);

    livewire(ListDowntimes::class)
        ->assertSee($this->insideEmployee->full_name)
        ->assertDontSee($this->outsideEmployee->full_name);
});

it('shows deductions scoped to the executive projects', function (): void {
    $insideDeduction = Deduction::factory()->create([
        'employee_id' => $this->insideEmployee->id,
        'payable_date' => now()->toDateString(),
    ]);

    $outsideDeduction = Deduction::factory()->create([
        'employee_id' => $this->outsideEmployee->id,
        'payable_date' => now()->toDateString(),
    ]);

    livewire(ListDeductions::class)
        ->assertSee($this->insideEmployee->full_name)
        ->assertDontSee($this->outsideEmployee->full_name);
});
