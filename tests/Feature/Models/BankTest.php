<?php

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Employee;

test('banks model interacts with db table', function () {
    $data = Bank::factory()->make();

    Bank::create($data->toArray());

    $this->assertDatabaseHas('banks', $data->only([
        'name', 'person_of_contact', 'description'
    ]));
});

test('bank model uses soft delete', function () {
    $bank = Bank::factory()->create();

    $bank->delete();

    $this->assertSoftDeleted(Bank::class, [
        'id' => $bank->id
    ]);
});

test('banks model morph one information', function () {
    $bank = Bank::factory()->create();

    expect($bank->information())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphOne::class);
});

test('has many accounts', function () {
    $bank = Bank::factory()->create();

    expect($bank->accounts->first())->toBeInstanceOf(BankAccount::class);
    expect($bank->accounts())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('banks model has many employees', function () {
    $bank = Bank::factory()->create();

    expect($bank->employees->first())->toBeInstanceOf(Employee::class);
    expect($bank->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
