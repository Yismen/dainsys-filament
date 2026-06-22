<?php

use App\Enums\EmployeeStatuses;
use App\Models\Afp;
use App\Models\Employee;
use App\Models\SocialSecurity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

test('afps model interacts with db table', function (): void {
    $data = Afp::factory()->make();

    Afp::create($data->toArray());

    $this->assertDatabaseHas(Afp::class, $data->only([
        'name', 'person_of_contact', 'phone', 'description',
    ]));
});

test('afps model has many social securities', function (): void {
    $afp = Afp::factory()
        ->hasSocialSecurities(1)
        ->create();

    expect($afp->socialSecurities->first())->toBeInstanceOf(SocialSecurity::class);
    expect($afp->socialSecurities())->toBeInstanceOf(HasMany::class);
});

test('afps model has hired employees', function (): void {
    $employee = Employee::factory()->create();
    $employee->status = EmployeeStatuses::Hired;
    $employee->saveQuietly();
    $afp = Afp::factory()
        ->hasSocialSecurities(1, ['employee_id' => $employee->id])
        ->create();

    expect($afp->hiredEmployees->first())->toBeInstanceOf(Employee::class);
    expect($afp->hiredEmployees())->toBeInstanceOf(HasManyThrough::class);
});

test('afps model has many employees', function (): void {
    $employee = Employee::factory()->create();
    $afp = Afp::factory()
        ->hasSocialSecurities(1, [
            'employee_id' => $employee->id,
        ])
        ->create();

    expect($afp->employees->first())->toBeInstanceOf(Employee::class);
    expect($afp->employees())->toBeInstanceOf(HasManyThrough::class);
});
