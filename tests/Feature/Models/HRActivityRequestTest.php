<?php

use App\Enums\HRActivityTypes;
use App\Models\Employee;
use App\Models\HRActivityRequest;
use App\Models\Supervisor;
use Illuminate\Support\Facades\Event;

test('hr activity request model interacts with db table', function () {
    Event::fake();
    $data = HRActivityRequest::factory()->make();

    HRActivityRequest::create($data->toArray());

    $this->assertDatabaseHas('h_r_activity_requests', [
        'employee_id' => $data->employee_id,
        'supervisor_id' => $data->supervisor_id,
        'activity_type' => $data->activity_type->value,
    ]);
});

test('hr activity request belongs to employee', function () {
    Event::fake();
    $request = HRActivityRequest::factory()->create();

    expect($request->employee)->toBeInstanceOf(Employee::class);
    expect($request->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('hr activity request belongs to supervisor', function () {
    Event::fake();
    $request = HRActivityRequest::factory()->create();

    expect($request->supervisor)->toBeInstanceOf(Supervisor::class);
    expect($request->supervisor())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('hr activity types enum has all required cases', function () {
    expect(HRActivityTypes::cases())->toHaveCount(7);
    expect(HRActivityTypes::Vacations->value)->toBe('Vacations');
    expect(HRActivityTypes::Permission->value)->toBe('Permission');
    expect(HRActivityTypes::EmploymentLetter->value)->toBe('Employment Letter');
    expect(HRActivityTypes::Loan->value)->toBe('Loan');
    expect(HRActivityTypes::Uniform->value)->toBe('Uniform');
    expect(HRActivityTypes::Counseling->value)->toBe('Counseling');
    expect(HRActivityTypes::Interview->value)->toBe('Interview');
});
