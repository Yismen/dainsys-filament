<?php

use App\Models\Campaign;
use App\Models\InvoiceAgent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('campaign must belong to an agent', function (): void {
    $agent = InvoiceAgent::factory()->create();
    $campaign = Campaign::factory()->create(['invoice_agent_id' => $agent->id]);
    expect($campaign->invoiceAgent)->toBeInstanceOf(InvoiceAgent::class);
    expect($campaign->invoiceAgent())->toBeInstanceOf(BelongsTo::class);
});

test('agent has many campaigns', function (): void {
    $agent = InvoiceAgent::factory()->create();
    Campaign::factory()->count(2)->create(['invoice_agent_id' => $agent->id]);
    $agent->refresh();
    expect($agent->campaigns)->toHaveCount(2);
    expect($agent->campaigns())->toBeInstanceOf(HasMany::class);
});
