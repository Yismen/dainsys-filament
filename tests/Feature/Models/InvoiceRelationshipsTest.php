<?php

use App\Models\Campaign;
use App\Models\Invoice;
use App\Models\InvoiceAgent;
use App\Models\Project;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('invoice belongs to a project, agent and campaign', function (): void {
    $project = Project::factory()->create();
    $agent = InvoiceAgent::factory()->create(['project_id' => $project->id]);
    $campaign = Campaign::factory()->create(['invoice_agent_id' => $agent->id]);
    $invoice = Invoice::factory()->create([
        'project_id' => $project->id,
        'agent_id' => $agent->id,
        'campaign_id' => $campaign->id,
    ]);

    expect($invoice->project)->toBeInstanceOf(Project::class);
    expect($invoice->agent)->toBeInstanceOf(InvoiceAgent::class);
    expect($invoice->campaign)->toBeInstanceOf(Campaign::class);
    expect($invoice->project())->toBeInstanceOf(BelongsTo::class);
    expect($invoice->agent())->toBeInstanceOf(BelongsTo::class);
    expect($invoice->campaign())->toBeInstanceOf(BelongsTo::class);
});

test('project has many invoices', function (): void {
    $project = Project::factory()->create();
    Invoice::factory()->count(2)->create(['project_id' => $project->id]);
    $project->refresh();
    expect($project->invoices)->toHaveCount(2);
    expect($project->invoices())->toBeInstanceOf(HasMany::class);
});

test('agent has invoices', function (): void {
    $agent = InvoiceAgent::factory()->create();
    Invoice::factory()->count(2)->create(['agent_id' => $agent->id]);
    $agent->refresh();
    expect($agent->invoices)->toHaveCount(2);
    expect($agent->invoices())->toBeInstanceOf(HasMany::class);
});

test('campaign has invoices', function (): void {
    $campaign = Campaign::factory()->create();
    Invoice::factory()->count(2)->create(['campaign_id' => $campaign->id]);
    $campaign->refresh();
    expect($campaign->invoices)->toHaveCount(2);
    expect($campaign->invoices())->toBeInstanceOf(HasMany::class);
});
