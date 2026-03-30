<?php

use App\Filament\Employee\Pages\EmployeeDashboard;
use App\Models\Citizenship;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses()->group('employee-panel');

beforeEach(function (): void {
    Mail::fake();
    Cache::flush();
});

it('renders the employee dashboard for an authenticated employee', function (): void {
    $employee = Employee::factory()->create([
        'citizenship_id' => Citizenship::factory()->create()->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subDays(10),
    ]);

    $employee->refresh();

    /** @var User $user */
    $user = User::factory()->create([
        'employee_id' => $employee->id,
    ]);

    actingAs($user);

    get(EmployeeDashboard::getUrl(panel: 'employee'))
        ->assertSuccessful();
});

it('can load dashboard widgets from cache on subsequent requests', function (): void {
    $employee = Employee::factory()->create([
        'citizenship_id' => Citizenship::factory()->create()->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subDays(10),
    ]);

    $employee->refresh();

    /** @var User $user */
    $user = User::factory()->create([
        'employee_id' => $employee->id,
    ]);

    actingAs($user);

    get(EmployeeDashboard::getUrl(panel: 'employee'))
        ->assertSuccessful();

    get(EmployeeDashboard::getUrl(panel: 'employee'))
        ->assertSuccessful();
});
