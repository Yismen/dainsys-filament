<?php

use App\Filament\Employee\Pages\MyNightlyHours;
use App\Models\Employee;
use App\Models\NightlyHour;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->employee = Employee::factory()->create();
    $this->user = User::factory()->create([
        'employee_id' => $this->employee->id,
    ]);
});

it('requires users to be authenticated to access my nightly hours', function () {
    $response = get(MyNightlyHours::getUrl(panel: 'employee'));

    $response->assertRedirect(route('filament.employee.auth.login'));
});

it('requires users to have an employee record', function () {
    $userWithoutEmployee = User::factory()->create([
        'employee_id' => null,
    ]);

    actingAs($userWithoutEmployee);

    get(MyNightlyHours::getUrl(panel: 'employee'))
        ->assertForbidden();
});

it('displays only the authenticated employees nightly hours', function () {
    $otherEmployee = Employee::factory()->create();

    $myNightlyHours = NightlyHour::factory()->count(3)->create([
        'employee_id' => $this->employee->id,
    ]);

    $otherNightlyHours = NightlyHour::factory()->count(2)->create([
        'employee_id' => $otherEmployee->id,
    ]);

    actingAs($this->user);

    livewire(MyNightlyHours::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords($myNightlyHours)
        ->assertCanNotSeeTableRecords($otherNightlyHours);
});

it('can filter nightly hours by date range', function () {
    NightlyHour::factory()->create([
        'employee_id' => $this->employee->id,
        'date' => '2025-01-15',
        'total_hours' => 2.5,
    ]);

    $inRangeRecord = NightlyHour::factory()->create([
        'employee_id' => $this->employee->id,
        'date' => '2025-02-15',
        'total_hours' => 3.0,
    ]);

    NightlyHour::factory()->create([
        'employee_id' => $this->employee->id,
        'date' => '2025-03-15',
        'total_hours' => 1.5,
    ]);

    actingAs($this->user);

    livewire(MyNightlyHours::class)
        ->filterTable('date', [
            'date_from' => '2025-02-01',
            'date_until' => '2025-02-28',
        ])
        ->assertCanSeeTableRecords([$inRangeRecord])
        ->assertCountTableRecords(1);
});

it('displays nightly hours sorted by date descending by default', function () {
    $older = NightlyHour::factory()->create([
        'employee_id' => $this->employee->id,
        'date' => '2025-01-01',
    ]);

    $newer = NightlyHour::factory()->create([
        'employee_id' => $this->employee->id,
        'date' => '2025-02-01',
    ]);

    actingAs($this->user);

    livewire(MyNightlyHours::class)
        ->assertCanSeeTableRecords([$newer, $older], inOrder: true);
});

it('displays total hours summary', function () {
    NightlyHour::factory()->create([
        'employee_id' => $this->employee->id,
        'total_hours' => 2.5,
    ]);

    NightlyHour::factory()->create([
        'employee_id' => $this->employee->id,
        'total_hours' => 3.0,
    ]);

    actingAs($this->user);

    livewire(MyNightlyHours::class)
        ->assertSuccessful();
});
