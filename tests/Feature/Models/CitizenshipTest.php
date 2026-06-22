<?php

use App\Enums\EmployeeStatuses;
use App\Models\Citizenship;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('citizenships model interacts with db table', function (): void {
    $data = Citizenship::factory()->make();

    Citizenship::create($data->toArray());

    $this->assertDatabaseHas('citizenships', $data->only([
        'name', 'description',
    ]));
});

test('citizenship model has many employees', function (): void {
    $citizenship = Citizenship::factory()->create();
    $employee = Employee::factory()->create(['citizenship_id' => $citizenship->id]);

    expect($citizenship->employees->first())->toBeInstanceOf(Employee::class);
    expect($citizenship->employees())->toBeInstanceOf(HasMany::class);
});

test('citizenship model has hired employees', function (): void {
    $citizenship = Citizenship::factory()->create();
    $employee = Employee::factory()->create(['citizenship_id' => $citizenship->id]);
    $employee->status = EmployeeStatuses::Hired;
    $employee->saveQuietly();

    expect($citizenship->hiredEmployees->first())->toBeInstanceOf(Employee::class);
    expect($citizenship->hiredEmployees())->toBeInstanceOf(HasMany::class);
});
