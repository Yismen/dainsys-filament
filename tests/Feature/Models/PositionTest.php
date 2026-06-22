<?php

use App\Enums\EmployeeStatuses;
use App\Enums\SalaryTypes;
use App\Events\EmployeeHiredEvent;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Position;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Event;

test('position model interacts with positions table', function (): void {
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

test('positions model belongs to department', function (): void {
    $position = Position::factory()->create();

    expect($position->department)->toBeInstanceOf(Department::class);
    expect($position->department())->toBeInstanceOf(BelongsTo::class);
});

it('cast salary as money', function (): void {
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

test('position model has many hires', function (): void {
    Event::fake([
        EmployeeHiredEvent::class,
    ]);

    $position = Position::factory()
        ->hasHires()
        ->create();

    expect($position->hires->first())->toBeInstanceOf(Hire::class);
    expect($position->hires())->toBeInstanceOf(HasMany::class);
});

test('position model has hired employees', function (): void {
    $employee = Employee::factory()->create();
    $position = Position::factory()
        ->hasHires(1, ['employee_id' => $employee->id])
        ->create();
    $employee->status = EmployeeStatuses::Hired;
    $employee->saveQuietly();

    expect($position->hiredEmployees->first())->toBeInstanceOf(Employee::class);
    expect($position->hiredEmployees())->toBeInstanceOf(HasMany::class);
});

test('position model has many employees', function (): void {
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
    expect($position->employees())->toBeInstanceOf(HasMany::class);
});

it('updates the description field', function (): void {
    Event::fake([
        EmployeeHiredEvent::class,
    ]);
    $employee = Employee::factory()->create();
    $position = Position::factory()
        ->hasHires(1, [
            'employee_id' => $employee->id,
        ])
        ->create();

    expect($position->details)->toBe(implode(', ', [
        $position->name,
        $position->department->name,
        '$'.$position->salary,
        $position->salary_type->name,
    ]));

});

it('calculates hourly rate correctly when salary type is salary', function (): void {
    $position = Position::factory()->create([
        'salary_type' => SalaryTypes::Salary,
        'salary' => 190640.00, // This should result in an hourly rate of 1000
    ]);

    expect($position->hourly_rate)->toBe(1000.0000000000001) // Due to floating point precision, we get a very small error here. This is expected.
        ->toBeFloat();
});

it('calculates hourly rate correctly when salary type is hourly', function (): void {
    $position = Position::factory()->create([
        'salary_type' => SalaryTypes::Hourly,
        'salary' => 50, // This should result in an hourly rate of 50
    ]);

    expect($position->hourly_rate)->toBe(50.0);
});

it('calculates hourly rate correctly when salary type is by sales', function (): void {
    $position = Position::factory()->create([
        'salary_type' => SalaryTypes::BySales,
        'salary' => 50, // This should result in an hourly rate of 50
    ]);

    expect($position->hourly_rate)->toBe(50.0);
});
