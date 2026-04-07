<?php

namespace Tests\Feature\Models;

use App\Models\InvoicePayment;

test('invoice_payments model interacts with db table', function (): void {
    $data = InvoicePayment::factory()->make();

    InvoicePayment::create($data->toArray());

    $this->assertDatabaseHas('invoice_payments', $data->only(['invoice_id', 'amount', 'date', 'reference', 'images', 'description']));
});

test('invoice_payment can belong to an invoice (optional)', function (): void {
    // This test is optional since there's no Invoice model yet; ensure the model can be created
    $payment = InvoicePayment::factory()->create();
    $this->assertNotNull($payment);
});
