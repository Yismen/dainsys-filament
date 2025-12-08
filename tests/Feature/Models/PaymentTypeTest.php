<?php

use App\Models\PaymentType;

test('payment types model interacts with db table', function () {
    $data = PaymentType::factory()->make();

    PaymentType::create($data->toArray());

    $this->assertDatabaseHas('payment_types', $data->only([
        'name', 'description'
    ]));
});

test('payment type model uses soft delete', function () {
    $payment_type = PaymentType::factory()->create();

    $payment_type->delete();

    $this->assertSoftDeleted(PaymentType::class, [
        'id' => $payment_type->id
    ]);
});

test('payment types model has many positions', function () {
    $payment_type = PaymentType::factory()->create();

    expect($payment_type->positions->first())->toBeInstanceOf(\App\Models\Position::class);
    expect($payment_type->positions())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
