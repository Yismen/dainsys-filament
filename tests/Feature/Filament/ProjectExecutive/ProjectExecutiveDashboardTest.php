<?php

use App\Filament\ProjectExecutive\Pages\ProjectExecutiveDashboard;
use App\Models\Role;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

function createProjectExecutiveDashboardUser(): User
{
    $role = Role::firstOrCreate(['name' => 'Project Executive Manager', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('project-executive'),
    );

    $user = createProjectExecutiveDashboardUser();

    actingAs($user);
});

it('renders the project executive dashboard', function (): void {
    livewire(ProjectExecutiveDashboard::class)
        ->assertSuccessful();
});

it('displays all project executive dashboard widgets', function (): void {
    livewire(ProjectExecutiveDashboard::class)
        ->assertSuccessful()
        ->assertSeeHtml('ProjectExecutiveStatsOverview')
        ->assertSeeHtml('EmployeesByProjectChart')
        ->assertSeeHtml('DailyRevenueByProjectChart')
        ->assertSeeHtml('DailyEfficiencyByProjectChart')
        ->assertSeeHtml('DailySphPercentageByProjectChart')
        ->assertSeeHtml('UpcomingBirthdaysTable')
        ->assertSeeHtml('AbsencesByEmployeeTable');
});
