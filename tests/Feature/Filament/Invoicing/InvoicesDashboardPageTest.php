<?php

use App\Filament\Invoicing\Pages\InvoicingDashboardPage;
use App\Filament\Invoicing\Widgets\OutstandingInvoicesTable;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(Filament::getPanel('invoicing'));
});

it('requires authentication to access invoices dashboard page', function (): void {
    $response = get(route(InvoicingDashboardPage::getRouteName()));

    $response->assertRedirect(route('filament.invoicing.auth.login'));
});

it('allows super admin users to access invoices dashboard page', function (): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route(InvoicingDashboardPage::getRouteName()));

    $response->assertOk();
});

it('keeps outstanding invoices table responsive to table filters', function (): void {
    actingAs($this->createSuperAdminUser());

    $clientA = Client::factory()->create(['name' => 'Client A']);
    $projectA = Project::factory()->create(['client_id' => $clientA->id, 'name' => 'Project A']);
    $clientB = Client::factory()->create(['name' => 'Client B']);
    $projectB = Project::factory()->create(['client_id' => $clientB->id, 'name' => 'Project B']);

    $invoiceA = Invoice::factory()->create([
        'project_id' => $projectA->id,
        'date' => '2026-04-02',
        'items' => [['name' => 'Service', 'price' => 400, 'quantity' => 1]],
    ]);

    $invoiceB = Invoice::factory()->create([
        'project_id' => $projectB->id,
        'date' => '2026-04-03',
        'items' => [['name' => 'Service', 'price' => 500, 'quantity' => 1]],
    ]);

    livewire(OutstandingInvoicesTable::class)
        ->assertCanSeeTableRecords([$invoiceA, $invoiceB])
        ->filterTable('project_id', $projectA->id)
        ->assertCanSeeTableRecords([$invoiceA])
        ->assertCanNotSeeTableRecords([$invoiceB]);
});
