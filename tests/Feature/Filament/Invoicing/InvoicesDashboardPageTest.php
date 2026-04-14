<?php

use App\Filament\Invoicing\Pages\InvoicingDashboardPage;
use App\Filament\Invoicing\Widgets\Concerns\InteractsWithInvoiceDashboardFilters;
use App\Filament\Invoicing\Widgets\OutstandingInvoicesTable;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

class InvoiceDashboardFilterProbe
{
    use InteractsWithInvoiceDashboardFilters;

    public function filteredInvoiceIds(): array
    {
        return $this->applyDashboardFiltersToInvoiceQuery(Invoice::query())
            ->pluck('id')
            ->toArray();
    }
}

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

it('applies invoice dashboard global filters to summary and chart queries', function (): void {
    $firstClient = Client::factory()->create(['name' => 'Client A']);
    $firstProject = Project::factory()->create([
        'client_id' => $firstClient->id,
        'name' => 'Project A',
    ]);

    $secondClient = Client::factory()->create(['name' => 'Client B']);
    $secondProject = Project::factory()->create([
        'client_id' => $secondClient->id,
        'name' => 'Project B',
    ]);

    $firstInvoice = Invoice::factory()->create([
        'project_id' => $firstProject->id,
        'date' => '2026-04-01',
        'items' => [['name' => 'Service', 'price' => 200, 'quantity' => 1]],
    ]);

    $secondInvoice = Invoice::factory()->create([
        'project_id' => $secondProject->id,
        'date' => '2026-02-10',
        'items' => [['name' => 'Service', 'price' => 300, 'quantity' => 1]],
    ]);

    $filterProbe = new InvoiceDashboardFilterProbe;

    $filterProbe->pageFilters = [
        'start_date' => '2026-03-01',
        'end_date' => '2026-04-30',
        'client_id' => $firstClient->id,
        'project_id' => $firstProject->id,
    ];

    expect($filterProbe->filteredInvoiceIds())
        ->toContain($firstInvoice->id)
        ->not->toContain($secondInvoice->id);
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
