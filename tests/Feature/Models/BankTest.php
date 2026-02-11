<?php

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Employee;

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
    expect($bank->bankAccounts())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('bank model has many employees', function (): void {
    $bank = Bank::factory()
        ->has(BankAccount::factory())
        ->create();

    expect($bank->employees->first())->toBeInstanceOf(Employee::class);
    expect($bank->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class);
});
