<?php

use App\Models\Campaign;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceAgent;
use App\Models\InvoicePayment;
use App\Models\Item;
use App\Models\Project;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('invoice_agent belongs to a project', function (): void {
    $project = Project::factory()->create();
    $agent = InvoiceAgent::factory()->create(['project_id' => $project->id]);
    $this->assertInstanceOf(Project::class, $agent->project);
    $this->assertInstanceOf(BelongsTo::class, $agent->project());
});

test('project has many invoice_agents', function (): void {
    $project = Project::factory()->create();
    InvoiceAgent::factory()->count(2)->create(['project_id' => $project->id]);
    $project->refresh();
    $this->assertCount(2, $project->invoiceAgents);
});

test('campaign must belong to an agent', function (): void {
    $agent = InvoiceAgent::factory()->create();
    $campaign = Campaign::factory()->create(['invoice_agent_id' => $agent->id]);
    $this->assertInstanceOf(InvoiceAgent::class, $campaign->invoiceAgent);
    $this->assertInstanceOf(BelongsTo::class, $campaign->invoiceAgent());
});

test('agent has many campaigns', function (): void {
    $agent = InvoiceAgent::factory()->create();
    Campaign::factory()->count(2)->create(['invoice_agent_id' => $agent->id]);
    $agent->refresh();
    $this->assertCount(2, $agent->campaigns);
    $this->assertInstanceOf(HasMany::class, $agent->campaigns());
});

test('project belongs to a client', function (): void {
    $client = Client::factory()->create();
    $project = Project::factory()->create(['client_id' => $client->id]);
    $this->assertInstanceOf(Client::class, $project->client);
    $this->assertInstanceOf(BelongsTo::class, $project->client());
});

test('client has multiple projects', function (): void {
    $client = Client::factory()->create();
    Project::factory()->count(2)->create(['client_id' => $client->id]);
    $client->refresh();
    $this->assertCount(2, $client->projects);
    $this->assertInstanceOf(HasMany::class, $client->projects());
});

test('payment belongs to an invoice', function (): void {
    $invoice = Invoice::factory()->create();
    $payment = InvoicePayment::factory()->create(['invoice_id' => $invoice->id]);
    $this->assertInstanceOf(Invoice::class, $payment->invoice);
    $this->assertInstanceOf(BelongsTo::class, $payment->invoice());
});

test('invoice has many payments', function (): void {
    $invoice = Invoice::factory()->create();
    InvoicePayment::factory()->count(3)->create(['invoice_id' => $invoice->id]);
    $invoice->refresh();
    $this->assertCount(3, $invoice->payments);
    $this->assertInstanceOf(HasMany::class, $invoice->payments());
});

test('campaign has many items', function (): void {
    $campaign = Campaign::factory()->create();
    Item::factory()->count(2)->create(['campaign_id' => $campaign->id]);
    $campaign->refresh();
    $this->assertCount(2, $campaign->items);
    $this->assertInstanceOf(HasMany::class, $campaign->items());
});

test('item belongs to a campaign', function (): void {
    $campaign = Campaign::factory()->create();
    $item = Item::factory()->create(['campaign_id' => $campaign->id]);
    $this->assertInstanceOf(Campaign::class, $item->campaign);
    $this->assertInstanceOf(BelongsTo::class, $item->campaign());
});

test('project has invoices', function (): void {
    $project = Project::factory()->create();
    Invoice::factory()->count(2)->create(['project_id' => $project->id]);
    $project->refresh();
    $this->assertCount(2, $project->invoices);
    $this->assertInstanceOf(HasMany::class, $project->invoices());
});

test('agent has invoices', function (): void {
    $agent = InvoiceAgent::factory()->create();
    Invoice::factory()->count(2)->create(['agent_id' => $agent->id]);
    $agent->refresh();
    $this->assertCount(2, $agent->invoices);
    $this->assertInstanceOf(HasMany::class, $agent->invoices());
});

test('campaign has invoices', function (): void {
    $campaign = Campaign::factory()->create();
    Invoice::factory()->count(2)->create(['campaign_id' => $campaign->id]);
    $campaign->refresh();
    $this->assertCount(2, $campaign->invoices);
    $this->assertInstanceOf(HasMany::class, $campaign->invoices());
});
