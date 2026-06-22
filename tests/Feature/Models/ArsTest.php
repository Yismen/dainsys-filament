<?php

use App\Enums\EmployeeStatuses;
use App\Models\Ars;
use App\Models\Employee;
use App\Models\SocialSecurity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

test('arss model interacts with db table', function (): void {
    $data = Ars::factory()->make();

    Ars::create($data->toArray());

    $this->assertDatabaseHas(Ars::class, $data->only([
        'name', 'person_of_contact', 'phone', 'description',
    ]));
});

test('arss model has many social securities', function (): void {
    $ars = Ars::factory()
        ->hasSocialSecurities(1)
        ->create();

    expect($ars->socialSecurities->first())->toBeInstanceOf(SocialSecurity::class);
    expect($ars->socialSecurities())->toBeInstanceOf(HasMany::class);
});

test('arss model has hired employees', function (): void {
    $employee = Employee::factory()->create();
    $employee->status = EmployeeStatuses::Hired;
    $employee->saveQuietly();
    $ars = Ars::factory()
        ->hasSocialSecurities(1, ['employee_id' => $employee->id])
        ->create();

    expect($ars->hiredEmployees->first())->toBeInstanceOf(Employee::class);
    expect($ars->hiredEmployees())->toBeInstanceOf(HasManyThrough::class);
});

test('arss model has many employees', function (): void {
    $employee = Employee::factory()->create();
    $ars = Ars::factory()
        ->hasSocialSecurities(1, [
            'employee_id' => $employee->id,
        ])
        ->create();

    expect($ars->employees->first())->toBeInstanceOf(Employee::class);
    expect($ars->employees())->toBeInstanceOf(HasManyThrough::class);
});
