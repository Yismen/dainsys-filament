<?php

use App\Models\BankAccount;

test('bank accounts model interacts with db table', function () {
    $data = BankAccount::factory()->make();

    BankAccount::create($data->toArray());

    $this->assertDatabaseHas('bank_accounts', $data->only([
        'employee_id', 'bank_id', 'account'
    ]));
});

test('bank accounts model belongs to employee', function () {
    $bank_account = BankAccount::factory()->create();

    expect($bank_account->employee)->toBeInstanceOf(\App\Models\Employee::class);
    expect($bank_account->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('bank accounts model belongs to bank', function () {
    $bank_account = BankAccount::factory()->create();

    expect($bank_account->bank)->toBeInstanceOf(\App\Models\Bank::class);
    expect($bank_account->bank())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
