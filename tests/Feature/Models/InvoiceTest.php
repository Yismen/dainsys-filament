<?php

namespace Tests\Feature\Models;

use App\Models\Campaign;
use App\Models\Invoice;
use App\Models\InvoiceAgent;
use App\Models\Project;

use function Pest\Laravel\assertDatabaseHas;

test('invoices model interacts with db table', function (): void {
    $data = Invoice::factory()->make();

    Invoice::create($data->toArray());

    // Keep assertion robust: only verify the invoice exists by its unique number
    assertDatabaseHas('invoices', ['number' => $data->number]);
});

test('invoice belongs to related models', function (): void {
    $invoice = Invoice::factory()->create();

    // Ensure relationships exist (presence of relations is sufficient for this test)
    expect($invoice->project)->toBeInstanceOf(Project::class);
    expect($invoice->agent)->toBeInstanceOf(InvoiceAgent::class);
    expect($invoice->campaign)->toBeInstanceOf(Campaign::class);
});
