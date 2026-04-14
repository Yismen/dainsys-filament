<?php

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('invoice number format generation for client and project', function (): void {
    // Controlled client and project names
    $client = Client::factory()->create(['name' => 'Acme Global Co']);
    $project = Project::factory()->for($client, 'client')->create(['name' => 'Nova Project One']);

    // Create first invoice
    $invoice1 = Invoice::factory()->for($project, 'project')->create();
    $prefix = 'ECC_AGC_NPO';
    $expected1 = $prefix.'_0001';
    expect($invoice1->number)->toBe($expected1);

    // Create second invoice for same project
    $invoice2 = Invoice::factory()->for($project, 'project')->create();
    $expected2 = $prefix.'_0002';
    expect($invoice2->number)->toBe($expected2);
});
