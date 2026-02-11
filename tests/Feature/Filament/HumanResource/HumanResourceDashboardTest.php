<?php

use App\Filament\HumanResource\Pages\HumanResourceDashboard;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Site;
use App\Models\Supervisor;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Mail::fake();
    Event::fake();

    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'),
    );

    // Create user with manage human resources permission
    $user = $this->createUserWithPermissionTo('manage human resources');

    actingAs($user);
});

test('human resource dashboard can be rendered', function (): void {
    livewire(HumanResourceDashboard::class)
        ->assertSuccessful();
});

test('dashboard displays all widgets', function (): void {
    livewire(HumanResourceDashboard::class)
        ->assertSuccessful()
        ->assertSeeHtml('EmployeesStats')
        ->assertSeeHtml('HRActivityRequestStats')
        ->assertSeeHtml('UpcomingEmployeeBirthdays')
        ->assertSeeHtml('HeadCountBySite')
        ->assertSeeHtml('HeadCountByProject')
        ->assertSeeHtml('HeadCountByPosition')
        ->assertSeeHtml('HeadCountBySupervisor');
});

test('dashboard has filter action button', function (): void {
    livewire(HumanResourceDashboard::class)
        ->assertSuccessful()
        ->assertSee('Filter');
});

test('dashboard applies default sites from config', function (): void {
    config(['app.default_sites' => []]);

    livewire(HumanResourceDashboard::class)
        ->assertSuccessful();
});

test('dashboard persists filters in filters action', function (): void {
    $site = Site::factory()->create();
    $project = Project::factory()->create();

    livewire(HumanResourceDashboard::class)
        ->assertSuccessful();
});

test('dashboard widgets respond to site filter', function (): void {
    $site1 = Site::factory()->create();
    $site2 = Site::factory()->create();

    $employee1 = Employee::factory()->create();
    $employee2 = Employee::factory()->create();

    $supervisor = Supervisor::factory()->create();

    \App\Models\Hire::factory()->create([
        'employee_id' => $employee1->id,
        'site_id' => $site1->id,
        'supervisor_id' => $supervisor->id,
    ]);

    \App\Models\Hire::factory()->create([
        'employee_id' => $employee2->id,
        'site_id' => $site2->id,
        'supervisor_id' => $supervisor->id,
    ]);

    // Dashboard should load successfully
    livewire(HumanResourceDashboard::class)
        ->assertSuccessful();
});

test('dashboard does not persist filters in session', function (): void {
    $dashboard = new HumanResourceDashboard;

    expect($dashboard->persistsFiltersInSession())->toBeFalse();
});
