<?php

use App\Models\Bank;

test('banks model interacts with db table', function () {
    $data = Bank::factory()->make();

    Bank::create($data->toArray());

    $this->assertDatabaseHas('banks', $data->only([
        'name', 'description'
    ]));
});

test('bank model uses soft delete', function () {
    $bank = Bank::factory()->create();

    $bank->delete();

    $this->assertSoftDeleted(Bank::class, $bank->toArray());
});

test('banks model morph one information', function () {
    $bank = Bank::factory()->create();

    expect($bank->information())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphOne::class);
});

test('banks model has many employees', function () {
    $bank = Bank::factory()->create();

    expect($bank->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
