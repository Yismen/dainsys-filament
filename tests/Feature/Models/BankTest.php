<?php

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Employee;

test('banks model interacts with db table', function () {
    $data = Bank::factory()->make();

    Bank::create($data->toArray());

    $this->assertDatabaseHas('banks', $data->only([
        'name', 'person_of_contact', 'description',
    ]));
});

test('banks model morph one information', function () {
    $bank = Bank::factory()
        ->hasInformation()
        ->create();

    expect($bank->information)->toBeInstanceOf(\App\Models\Information::class);
    expect($bank->information())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphOne::class);
});

test('has many bank accounts', function () {
    $bank = Bank::factory()
        ->hasBankAccounts(1)
        ->create();

    expect($bank->bankAccounts->first())->toBeInstanceOf(BankAccount::class);
    expect($bank->bankAccounts())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('bank model has many employees', function () {
    $bank = Bank::factory()
        ->has(BankAccount::factory())
        ->create();

    expect($bank->employees->first())->toBeInstanceOf(Employee::class);
    expect($bank->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class);
});
