<?php

use App\Enums\EmployeeStatuses;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

test('departments model interacts with db table', function (): void {
    $data = Department::factory()->make();

    Department::create($data->toArray());

    $this->assertDatabaseHas('departments', $data->only([
        'name', 'description',
    ]));
});

test('department model has hired employees', function (): void {
    $department = Department::factory()->create();
    $position = Position::factory()->create(['department_id' => $department->id]);
    $employee = Employee::factory()->create(['position_id' => $position->id]);
    $employee->status = EmployeeStatuses::Hired;
    $employee->saveQuietly();

    expect($department->hiredEmployees->first())->toBeInstanceOf(Employee::class);
    expect($department->hiredEmployees())->toBeInstanceOf(HasManyThrough::class);
});

test('departments model has many positions', function (): void {
    $department = Department::factory()
        ->has(Position::factory())
        ->create();

    expect($department->positions->first())->toBeInstanceOf(Position::class);
    expect($department->positions())->toBeInstanceOf(HasMany::class);
});

// test('departments model has many employees thru positions', function () {
//     $department = Department::factory()->create();
//     $position = Position::factory()->create(['department_id' => $department->id]);
//     $employee = Employee::factory()->create();

//     Hire::factory()->create([
//         'employee_id' => $employee->id,
//         'position_id' => $position->id,
//     ]);

//     dd(
//         $department->positions->toArray(),
//         $department->employees->toArray(),
//         // $position->toArray(),
//         // $position->employees->toArray()
//     );

//     expect($department->employees->first())->toBeInstanceOf(Employee::class);
//     expect($department->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class);
// });
