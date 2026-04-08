<?php

namespace Tests\Feature\Models;

use App\Exceptions\InvoiceOverpaymentException;
use App\Models\Invoice;
use App\Models\InvoicePayment;

test('creating a payment reduces invoice balance_pending', function (): void {
    $invoice = Invoice::factory()->create(['balance_pending' => 100]);
    InvoicePayment::factory()->create(['invoice_id' => $invoice->id, 'amount' => 40]);
    $invoice->refresh();
    expect($invoice->balance_pending)->toBe(60);
});

test('overpayment throws exception', function (): void {
    $invoice = Invoice::factory()->create(['balance_pending' => 20]);
    $this->expectException(InvoiceOverpaymentException::class);
    InvoicePayment::factory()->create(['invoice_id' => $invoice->id, 'amount' => 50]);
});

test('updating a payment adjusts balance accordingly', function (): void {
    $invoice = Invoice::factory()->create(['balance_pending' => 100]);
    $payment = InvoicePayment::factory()->create(['invoice_id' => $invoice->id, 'amount' => 20]);
    $payment->update(['amount' => 50]);
    $invoice->refresh();
    expect($invoice->balance_pending)->toBe(50);
});

test('deleting a payment restores balance', function (): void {
    $invoice = Invoice::factory()->create(['balance_pending' => 100]);
    $payment = InvoicePayment::factory()->create(['invoice_id' => $invoice->id, 'amount' => 30]);
    $payment->delete();
    $invoice->refresh();
    expect($invoice->balance_pending)->toBe(100);
});
