<?php

use App\Models\InvoiceAgent;
use App\Models\Project;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('invoice_agent belongs to a project', function (): void {
    $project = Project::factory()->create();
    $agent = InvoiceAgent::factory()->create(['project_id' => $project->id]);
    expect($agent->project)->toBeInstanceOf(Project::class);
    expect($agent->project())->toBeInstanceOf(BelongsTo::class);
});

test('project has many invoice_agents', function (): void {
    $project = Project::factory()->create();
    InvoiceAgent::factory()->count(2)->create(['project_id' => $project->id]);
    $project->refresh();
    expect($project->invoiceAgents)->toHaveCount(2);
    expect($project->invoiceAgents())->toBeInstanceOf(HasMany::class);
});
