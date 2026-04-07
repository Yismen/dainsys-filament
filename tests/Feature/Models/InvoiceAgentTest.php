<?php

use App\Models\InvoiceAgent;
use App\Models\Project;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

beforeEach(function (): void {
    // No special setup required for these tests yet
});

test('invoice_agents model interacts with db table', function (): void {
    $data = InvoiceAgent::factory()->make();

    InvoiceAgent::create($data->toArray());

    // verify persistence
    $this->assertDatabaseHas('invoice_agents', $data->only(['name', 'project_id', 'phone', 'email']));
});

test('invoice_agent belongs to project', function (): void {
    $invoiceAgent = InvoiceAgent::factory()->create();

    expect($invoiceAgent->project)->toBeInstanceOf(Project::class);
    expect($invoiceAgent->project())->toBeInstanceOf(BelongsTo::class);
});
