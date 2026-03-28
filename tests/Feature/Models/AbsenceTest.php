<?php

use App\Enums\AbsenceStatuses;
use App\Enums\AbsenceTypes;
use App\Models\Absence;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\assertDatabaseHas;

test('absences model interacts with db table', function (): void {
    $data = Absence::factory()->make();

    Absence::create($data->toArray());

    assertDatabaseHas('absences', $data->only([
        'employee_id', 'status', 'comment',
    ]));
});

test('absences model belongs to employee', function (): void {
    $absence = Absence::factory()->create();

    expect($absence->employee)->toBeInstanceOf(Employee::class);
    expect($absence->employee())->toBeInstanceOf(BelongsTo::class);
});

test('absences model belongs to creator', function (): void {
    $absence = Absence::factory()->create();

    expect($absence->creator)->toBeInstanceOf(User::class);
    expect($absence->creator())->toBeInstanceOf(BelongsTo::class);
});

// test('absences model has current month scope', function (): void {
//     $absence = Absence::factory()->create([
//         'date' => now()->format('Y-m-d'),
//     ]);

//     expect(Absence::currentMonth()->get())->toContain($absence->id);
// });

test('absences model can mark as reported', function (): void {
    $absence = Absence::factory()->create([
        'status' => AbsenceStatuses::Created,
        'type' => null,
    ]);

    $absence->markAsReported(AbsenceTypes::Justified);

    expect($absence->fresh()->status)->toBe(AbsenceStatuses::Reported);
    expect($absence->fresh()->type)->toBe(AbsenceTypes::Justified);
});

test('absences model checks red flagged status', function (): void {
    $employee = Employee::factory()->create();

    $absence1 = Absence::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subDay()->format('Y-m-d'),
    ]);

    expect($absence1->isRedFlagged())->toBeFalse();

    $absence2 = Absence::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->format('Y-m-d'),
    ]);

    expect($absence2->isRedFlagged())->toBeTrue();
});
