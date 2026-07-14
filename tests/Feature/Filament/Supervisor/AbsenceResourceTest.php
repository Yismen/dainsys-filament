<?php

use App\Enums\AbsenceStatuses;
use App\Filament\Supervisor\Resources\Absences\AbsenceResource;
use App\Models\Absence;
use App\Models\Employee;
use App\Models\Supervisor;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('supervisor'),
    );
});

it('supervisor can only see absences for their employees', function (): void {
    $user = User::factory()->create();
    $supervisor = Supervisor::factory()->create(['user_id' => $user->id]);

    $myEmployee = Employee::factory()->hired()->create(['supervisor_id' => $supervisor->id]);
    $otherEmployee = Employee::factory()->hired()->create();

    $myAbsence = Absence::factory()->create(['employee_id' => $myEmployee->id]);
    $otherAbsence = Absence::factory()->create(['employee_id' => $otherEmployee->id]);

    actingAs($user);

    $query = AbsenceResource::getEloquentQuery();

    expect($query->pluck('id'))->toContain($myAbsence->id);
    expect($query->pluck('id'))->not->toContain($otherAbsence->id);
});

it('supervisor without supervisor record sees no absences', function (): void {
    $user = User::factory()->create();

    $employee = Employee::factory()->hired()->create();
    Absence::factory()->create(['employee_id' => $employee->id]);

    actingAs($user);

    $query = AbsenceResource::getEloquentQuery();

    expect($query->count())->toBe(0);
});

it('absence status defaults to created', function (): void {
    $employee = Employee::factory()->hired()->create();
    $absence = Absence::factory()->create(['employee_id' => $employee->id]);

    expect($absence->status)->toBe(AbsenceStatuses::Created);
});

it('absence can be hard deleted', function (): void {
    $employee = Employee::factory()->hired()->create();
    $absence = Absence::factory()->create(['employee_id' => $employee->id]);

    expect(Absence::find($absence->id))->not->toBeNull();

    $absence->deleteOrFail();

    expect(Absence::find($absence->id))->toBeNull();
});
