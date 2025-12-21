<?php

use App\Models\Department;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Position;

test('departments model interacts with db table', function () {
    $data = Department::factory()->make();

    Department::create($data->toArray());

    $this->assertDatabaseHas('departments', $data->only([
        'name', 'description',
    ]));
});

test('departments model has many positions', function () {
    $department = Department::factory()
        ->has(Position::factory())
        ->create();

    expect($department->positions->first())->toBeInstanceOf(\App\Models\Position::class);
    expect($department->positions())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
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
