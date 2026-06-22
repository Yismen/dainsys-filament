<?php

use App\Enums\EmployeeStatuses;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

test('banks model interacts with db table', function (): void {
    $data = Bank::factory()->make();

    Bank::create($data->toArray());

    $this->assertDatabaseHas('banks', $data->only([
        'name', 'person_of_contact', 'phone', 'email', 'description',
    ]));
});

test('has many bank accounts', function (): void {
    $bank = Bank::factory()
        ->hasBankAccounts(1)
        ->create();

    expect($bank->bankAccounts->first())->toBeInstanceOf(BankAccount::class);
    expect($bank->bankAccounts())->toBeInstanceOf(HasMany::class);
});

test('bank model has hired employees', function (): void {
    $employee = Employee::factory()->create();
    $employee->status = EmployeeStatuses::Hired;
    $employee->saveQuietly();
    $bank = Bank::factory()
        ->has(BankAccount::factory(['employee_id' => $employee->id]))
        ->create();

    expect($bank->hiredEmployees->first())->toBeInstanceOf(Employee::class);
    expect($bank->hiredEmployees())->toBeInstanceOf(HasManyThrough::class);
});

test('bank model has many employees', function (): void {
    $bank = Bank::factory()
        ->has(BankAccount::factory())
        ->create();

    expect($bank->employees->first())->toBeInstanceOf(Employee::class);
    expect($bank->employees())->toBeInstanceOf(HasManyThrough::class);
});
