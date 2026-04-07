<?php

namespace Tests\Feature\Models;

use App\Models\Campaign;
use App\Models\Invoice;
use App\Models\InvoiceAgent;
use App\Models\Project;

test('invoices model interacts with db table', function (): void {
    $data = Invoice::factory()->make();

    Invoice::create($data->toArray());

    // Assert core fields; items is JSON, so skip exact match for now
    $this->assertDatabaseHas('invoices', [
        'number' => $data->number,
        'date' => $data->date,
        'project_id' => $data->project_id,
        'agent_id' => $data->agent_id,
        'campaign_id' => $data->campaign_id,
        'subtotal_amount' => $data->subtotal_amount,
        'tax_amount' => $data->tax_amount,
        'total_amount' => $data->total_amount,
        'total_paid' => $data->total_paid,
        'balance_pending' => $data->balance_pending,
        'status' => $data->status,
        'due_date' => $data->due_date,
    ]);
});

test('invoice belongs to related models', function (): void {
    $invoice = Invoice::factory()->create();

    // Ensure relationships exist (presence of relations is sufficient for this test)
    expect($invoice->project)->toBeInstanceOf(Project::class);
    expect($invoice->agent)->toBeInstanceOf(InvoiceAgent::class);
    expect($invoice->campaign)->toBeInstanceOf(Campaign::class);
});
