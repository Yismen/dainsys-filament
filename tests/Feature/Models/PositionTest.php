<?php

use App\Events\EmployeeHiredEvent;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Support\Facades\Event;

test('position model interacts with positions table', function () {
    $data = Position::factory()->make();


    Position::create($data->toArray());

    $this->assertDatabaseHas('positions', $data->only([
        'name',
        'department_id',
        'salary_type',
        // 'salary',
        'description',
    ]));
});

test('positions model belongs to department', function () {
    $position = Position::factory()->create();

    expect($position->department)->toBeInstanceOf(\App\Models\Department::class);
    expect($position->department())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

it('cast salary as money', function () {
    $data = Position::factory()->make([
        'salary' => 150,
    ]);

    $position = Position::create($data->toArray());

    expect((float) $position->salary)->toBe(150.0);
    $this->assertDatabaseHas('positions', [
        'id' => $position->id,
        'salary' => 15000,
    ]);
});

test('position model has many hires', function () {
    Event::fake([
        EmployeeHiredEvent::class,
    ]);

    $position = Position::factory()
        ->hasHires()
        ->create();

    expect($position->hires->first())->toBeInstanceOf(\App\Models\Hire::class);
    expect($position->hires())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('position model has many employees', function () {
    Event::fake([
        EmployeeHiredEvent::class,
    ]);
    $employee = Employee::factory()->create();
    $position = Position::factory()
        ->hasHires(1, [
            'employee_id' => $employee->id,
        ])
        ->create();

    expect($position->employees->first())->toBeInstanceOf(Employee::class);
    expect($position->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class);
});

it('updates the description field', function () {
    Event::fake([
        EmployeeHiredEvent::class,
    ]);
    $employee = Employee::factory()->create();
    $position = Position::factory()
        ->hasHires(1, [
            'employee_id' => $employee->id,
        ])
        ->create();

    expect($position->details)->toBe(join(', ', [
        $position->name,
        $position->department->name,
        '$' . $position->salary,
        $position->salary_type->name,
    ]));

});
