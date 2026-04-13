<?php

namespace Tests\Feature\Models;

use App\Enums\InvoiceStatuses;
use App\Exceptions\InvoiceCannotBeCancelledException;
use App\Models\Invoice;
use App\Models\InvoiceCancellation;
use App\Models\InvoicePayment;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('creating a cancellation auto-sets cancelled_by from authenticated user and marks invoice as cancelled', function (): void {
    $user = User::factory()->create();
    actingAs($user);

    $invoice = Invoice::factory()->create([
        'items' => [
            ['name' => 'Service', 'price' => 100, 'quantity' => 1],
        ],
    ]);

    $cancellation = InvoiceCancellation::create([
        'invoice_id' => $invoice->id,
        'date' => $invoice->date->toDateString(),
        'reason' => 'Client requested cancellation.',
    ]);

    $invoice->refresh();

    expect($cancellation->cancelled_by)->toBe($user->id);
    expect($invoice->status)->toBe(InvoiceStatuses::Cancelled);
});

test('cancelling a paid invoice throws exception', function (): void {
    $invoice = Invoice::factory()->create([
        'items' => [
            ['name' => 'Service', 'price' => 100, 'quantity' => 1],
        ],
    ]);

    InvoicePayment::factory()->create([
        'invoice_id' => $invoice->id,
        'amount' => 10,
    ]);

    $this->expectException(InvoiceCannotBeCancelledException::class);

    InvoiceCancellation::create([
        'invoice_id' => $invoice->id,
        'date' => $invoice->date->toDateString(),
        'reason' => 'Attempted cancellation after payment.',
    ]);
});
