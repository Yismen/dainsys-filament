<?php

use App\Models\BankAccount;
use Illuminate\Support\Facades\Mail;

test('bank accounts model interacts with db table', function () {
    Mail::fake();
    $data = BankAccount::factory()->make();

    BankAccount::create($data->toArray());

    $this->assertDatabaseHas('bank_accounts', $data->only([
        'employee_id', 'bank_id', 'account'
    ]));
});

test('bank account model uses soft delete', function () {
    Mail::fake();
    $bank_account = BankAccount::factory()->create();

    $bank_account->delete();

    $this->assertSoftDeleted(BankAccount::class, $bank_account->only(['id']));
});

test('bank accounts model belongs to employee', function () {
    Mail::fake();
    $bank_account = BankAccount::factory()->create();

    expect($bank_account->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('bank accounts model belongs to bank', function () {
    Mail::fake();
    $bank_account = BankAccount::factory()->create();

    expect($bank_account->bank())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
