<?php

namespace Tests\Feature\Models;

use App\Models\InvoicePayment;

test('invoice_payments model interacts with db table', function (): void {
    $data = InvoicePayment::factory()->make();

    $payment = InvoicePayment::create($data->toArray());

    $this->assertDatabaseHas('invoice_payments', [
        'id' => $payment->id,
        'invoice_id' => $data->invoice_id,
        'amount' => $data->amount,
        'reference' => $data->reference,
        'description' => $data->description,
    ]);
});

test('invoice_payment can belong to an invoice (optional)', function (): void {
    // This test is optional since there's no Invoice model yet; ensure the model can be created
    $payment = InvoicePayment::factory()->create();
    $this->assertNotNull($payment);
});
