<?php

use App\Events\EmployeeHiredEvent;
use App\Filament\ProjectExecutive\Widgets\UpcomingBirthdaysTable;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Filament\Facades\Filament;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    Mail::fake();
    Event::fake([EmployeeHiredEvent::class]);
    Filament::setCurrentPanel(Filament::getPanel('project-executive'));
});

test('project executive birthdays widget shows employees with birthdays in next 10 days', function (): void {
    $user = User::factory()->createOne();
    $project = Project::factory()->create(['manager_id' => $user->id]);
    $employee1 = Employee::factory()->create([
        'project_id' => $project->id,
        'date_of_birth' => now()->addDays(5)->setYear(1990),
    ]);
    Hire::factory()->create([
        'employee_id' => $employee1->id,
        'project_id' => $project->id,
    ]);
    $employee2 = Employee::factory()->create([
        'project_id' => $project->id,
        'date_of_birth' => now()->addDay()->setYear(1985),
    ]);
    Hire::factory()->create([
        'employee_id' => $employee2->id,
        'project_id' => $project->id,
    ]);
    $employee3 = Employee::factory()->create([
        'project_id' => $project->id,
        'date_of_birth' => now()->subDay()->setYear(1980),
    ]);
    Hire::factory()->create([
        'employee_id' => $employee3->id,
        'project_id' => $project->id,
    ]);
    $employee4 = Employee::factory()->create([
        'project_id' => $project->id,
        'date_of_birth' => now()->addDays(11)->setYear(1992),
    ]);
    Hire::factory()->create([
        'employee_id' => $employee4->id,
        'project_id' => $project->id,
    ]);
    actingAs($user);
    Livewire::test(UpcomingBirthdaysTable::class)
        ->assertSee($employee1->full_name)
        ->assertSee($employee2->full_name)
        ->assertDontSee($employee3->full_name)
        ->assertDontSee($employee4->full_name);
});

test('project executive birthdays widget sorts by month and day', function (): void {
    $user = User::factory()->createOne();
    $project = Project::factory()->create(['manager_id' => $user->id]);
    $employeeA = Employee::factory()->create([
        'project_id' => $project->id,
        'date_of_birth' => now()->addDays(2)->setYear(1990),
        'first_name' => 'A',
    ]);
    Hire::factory()->create([
        'employee_id' => $employeeA->id,
        'project_id' => $project->id,
    ]);
    $employeeB = Employee::factory()->create([
        'project_id' => $project->id,
        'date_of_birth' => now()->addDays(1)->setYear(1990),
        'first_name' => 'B',
    ]);
    Hire::factory()->create([
        'employee_id' => $employeeB->id,
        'project_id' => $project->id,
    ]);
    actingAs($user);
    Livewire::test(UpcomingBirthdaysTable::class)
        ->assertSeeInOrder([$employeeB->full_name, $employeeA->full_name]);
});
