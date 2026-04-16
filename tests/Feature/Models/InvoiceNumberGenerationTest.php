<?php

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('generate the full text for the client or project names when they are composed of just one word', function (): void {
    // Controlled client and project names
    $client = Client::factory()->create(['name' => 'Acme']);
    $project = Project::factory()->for($client, 'client')->create(['name' => 'Nova']);

    // Create first invoice
    $invoice1 = Invoice::factory()->for($project, 'project')->create();
    $prefix = 'ECC-ACME-NOVA';
    $expected1 = $prefix.'-0001';
    expect($invoice1->number)->toBe($expected1);
});

// Controlled client and project names
it('generate the full first word and the other words initials when the client name is composed of two words', function (): void {
    $client = Client::factory()->create(['name' => 'Acme Global']);
    $project = Project::factory()->for($client, 'client')->create(['name' => 'Nova Project']);

    // Create first invoice
    $invoice1 = Invoice::factory()->for($project, 'project')->create();
    $prefix = 'ECC-ACMEG-NOVAP';
    $expected1 = $prefix.'-0001';
    expect($invoice1->number)->toBe($expected1);
});

// Controlled client and project names
it('generate the full first word and the other words initials when the client name is composed of multiple words', function (): void {
    $client = Client::factory()->create(['name' => 'Acme Global Co']);
    $project = Project::factory()->for($client, 'client')->create(['name' => 'Nova Project One']);

    // Create first invoice
    $invoice1 = Invoice::factory()->for($project, 'project')->create();
    $prefix = 'ECC-ACMEGC-NOVAPO';
    $expected1 = $prefix.'-0001';
    expect($invoice1->number)->toBe($expected1);
});

// Controlled client and project names
it('generate the full first word and the initials of the next two words when the client name is composed of more than 3 parts', function (): void {
    $client = Client::factory()->create(['name' => 'Acme Global Co, Inc']);
    $project = Project::factory()->for($client, 'client')->create(['name' => 'Nova Project One Phase 1']);

    // Create first invoice
    $invoice1 = Invoice::factory()->for($project, 'project')->create();
    $prefix = 'ECC-ACMEGC-NOVAPO';
    $expected1 = $prefix.'-0001';
    expect($invoice1->number)->toBe($expected1);
});

test('increments when another invoice exists for the same client and project', function (): void {
    // Controlled client and project names
    $client = Client::factory()->create(['name' => 'Acme Global Co']);
    $project = Project::factory()->for($client, 'client')->create(['name' => 'Nova Project One']);

    // Create first invoice
    $invoice1 = Invoice::factory()->for($project, 'project')->create();
    $prefix = 'ECC-ACMEGC-NOVAPO';
    $expected1 = $prefix.'-0001';
    expect($invoice1->number)->toBe($expected1);

    // Create second invoice for same project
    $invoice2 = Invoice::factory()->for($project, 'project')->create();
    $expected2 = $prefix.'-0002';
    expect($invoice2->number)->toBe($expected2);
});
