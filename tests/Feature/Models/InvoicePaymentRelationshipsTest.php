<?php

use App\Models\Invoice;
use App\Models\InvoicePayment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('invoice_payment belongs to an invoice', function (): void {
    $invoice = Invoice::factory()->create();
    $payment = InvoicePayment::factory()->create(['invoice_id' => $invoice->id]);
    expect($payment->invoice)->toBeInstanceOf(Invoice::class);
    expect($payment->invoice())->toBeInstanceOf(BelongsTo::class);
});

test('invoice has payments', function (): void {
    $invoice = Invoice::factory()->create();
    InvoicePayment::factory()->count(3)->create(['invoice_id' => $invoice->id]);
    $invoice->refresh();
    expect($invoice->payments)->toHaveCount(3);
    expect($invoice->payments())->toBeInstanceOf(HasMany::class);
});
