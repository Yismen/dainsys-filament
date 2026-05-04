<?php

use App\Filament\Invoicing\Resources\Invoices\Pages\ManageInvoices;
use App\Models\Campaign;
use App\Models\Invoice;
use App\Models\InvoiceAgent;
use App\Models\Item;
use App\Models\Project;
use App\Models\User;
use App\Services\GenerateInvoicePdfService;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms\Components\Repeater;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(Filament::getPanel('invoicing'));

    $project = Project::factory()->create(['invoice_net_days' => 15]);
    $agent = InvoiceAgent::factory()->create(['project_id' => $project->id]);
    $campaign = Campaign::factory()->create([
        'project_id' => $project->id,
        'invoice_agent_id' => $agent->id,
    ]);
    $item = Item::query()->create([
        'name' => 'Main Service',
        'campaign_id' => $campaign->id,
        'price' => '250.50',
    ]);

    $this->resource_routes = [
        'index' => [
            'route' => ManageInvoices::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
    ];

    $this->form_data = [
        'date' => now()->toDateString(),
        'project_id' => $project->id,
        'agent_id' => $agent->id,
        'campaign_id' => $campaign->id,
        'items' => [
            [
                'item_id' => $item->id,
                'price' => (string) $item->price,
                'quantity' => 2,
            ],
        ],
    ];
});

it('require users to be authenticated to access Invoice resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.invoicing.auth.login'));
})->with(['index']);

it('require users to have correct permissions to access Invoice resource pages', function (string $method): void {
    actingAs(User::factory()->create());

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertForbidden();
})->with(['index']);

it('allows super admin users to access Invoice resource pages', function (string $method): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with(['index']);

it('allow users with correct permissions to access Invoice resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Invoice'));

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with(['index']);

it('displays Invoice list page correctly', function (): void {
    $invoices = Invoice::factory()->state(['status' => 'pending'])->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any Invoice'));

    livewire(ManageInvoices::class)
        ->assertCanSeeTableRecords($invoices);
});

test('creates Invoice from modal action', function (): void {
    $undoRepeaterFake = Repeater::fake();

    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Invoice'));

    livewire(ManageInvoices::class)
        ->callTableAction('create', data: $this->form_data)
        ->assertHasNoTableActionErrors();

    $undoRepeaterFake();

    $createdInvoice = Invoice::query()->latest('id')->firstOrFail();
    $expectedDueDate = Carbon::parse($this->form_data['date'])->addDays(15)->toDateString();

    expect($createdInvoice->number)->not->toBeEmpty()
        ->and($createdInvoice->project_id)->toBe($this->form_data['project_id'])
        ->and($createdInvoice->subtotal_amount)->toBeGreaterThan(0)
        ->and($createdInvoice->total_amount)->toBeGreaterThan(0)
        ->and((float) $createdInvoice->tax_amount)->toBe(0.0)
        ->and($createdInvoice->due_date?->toDateString())->toBe($expectedDueDate)
        ->and($createdInvoice->items)->toBeArray();
});

test('edits Invoice from modal action', function (): void {
    $undoRepeaterFake = Repeater::fake();

    $invoice = Invoice::factory()->state(['status' => 'pending'])->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Invoice'));

    livewire(ManageInvoices::class)
        ->callTableAction('edit', $invoice->getKey(), $this->form_data)
        ->assertHasNoTableActionErrors();

    $undoRepeaterFake();

    $updatedInvoice = $invoice->refresh();

    expect($updatedInvoice->project_id)->toBe($this->form_data['project_id'])
        ->and($updatedInvoice->subtotal_amount)->toBeGreaterThan(0)
        ->and($updatedInvoice->total_amount)->toBeGreaterThan(0);
});

test('form validation requires fields on create and edit modal actions', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Invoice'));

    livewire(ManageInvoices::class)
        ->callTableAction('create', data: [
            'date' => null,
            'project_id' => null,
            'items' => [],
        ])
        ->assertHasTableActionErrors([
            'date' => 'required',
            'project_id' => 'required',
            'items' => 'required',
        ]);

    $invoice = Invoice::factory()->state(['status' => 'pending'])->create();

    livewire(ManageInvoices::class)
        ->callTableAction('edit', $invoice->getKey(), [
            'date' => null,
            'project_id' => null,
            'items' => [],
        ])
        ->assertHasTableActionErrors([
            'date' => 'required',
            'project_id' => 'required',
            'items' => 'required',
        ]);
});

it('opens create, view and edit invoice modals from list page', function (): void {
    $invoice = Invoice::factory()->state(['status' => 'pending'])->create();

    actingAs($this->createUserWithPermissionsToActions(['create', 'view', 'update', 'view-any'], 'Invoice'));

    livewire(ManageInvoices::class)
        ->mountTableAction('create')
        ->assertOk()
        ->mountTableAction('view', $invoice->getKey())
        ->assertOk()
        ->mountTableAction('edit', $invoice->getKey())
        ->assertOk();
});

it('downloads invoice pdf from table action', function (): void {
    $invoice = Invoice::factory()->create([
        'items' => [
            [
                'name' => 'Design Service',
                'quantity' => 2,
                'price' => 250.5,
            ],
        ],
        'status' => 'pending',
    ]);

    actingAs($this->createUserWithPermissionTo('view-any Invoice'));

    livewire(ManageInvoices::class)
        ->callTableAction('downloadPdf', $invoice->getKey())
        ->assertFileDownloaded("invoice-{$invoice->number}.pdf");
});

it('streams invoice pdf for preview from service', function (): void {
    $invoice = Invoice::factory()->create([
        'items' => [
            [
                'name' => 'Consulting',
                'quantity' => 1,
                'price' => 125.75,
            ],
        ],
        'status' => 'pending',
    ]);

    $response = app(GenerateInvoicePdfService::class)->preview($invoice);

    expect($response->headers->get('content-type'))->toContain('application/pdf')
        ->and($response->headers->get('content-disposition'))->toContain('inline');
});
