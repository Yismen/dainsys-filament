<?php

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

test('bank accounts model interacts with db table', function (): void {
    $data = BankAccount::factory()->make();

    BankAccount::create($data->toArray());

    $this->assertDatabaseHas('bank_accounts', $data->only([
        'employee_id', 'bank_id', 'account',
    ]));
});

test('bank accounts model belongs to employee', function (): void {
    $bank_account = BankAccount::factory()->create();

    expect($bank_account->employee)->toBeInstanceOf(Employee::class);
    expect($bank_account->employee())->toBeInstanceOf(BelongsTo::class);
});

test('bank accounts model belongs to bank', function (): void {
    $bank_account = BankAccount::factory()->create();

    expect($bank_account->bank)->toBeInstanceOf(Bank::class);
    expect($bank_account->bank())->toBeInstanceOf(BelongsTo::class);
});
