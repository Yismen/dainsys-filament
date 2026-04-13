<?php

use App\Filament\Invoicing\Resources\InvoiceCancellations\Pages\ManageInvoiceCancellations;
use App\Models\Invoice;
use App\Models\InvoiceCancellation;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(Filament::getPanel('invoicing'));

    $this->invoice = Invoice::factory()->create([
        'items' => [['name' => 'Service', 'price' => 500.0, 'quantity' => 1]],
    ]);

    $this->user = User::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ManageInvoiceCancellations::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
    ];

    $this->form_data = [
        'invoice_id' => $this->invoice->id,
        'date' => now()->toDateString(),
        'reason' => 'Client requested cancellation due to budget cuts.',
        'notes' => 'Confirmed via email on '.now()->toDateString(),
    ];
});

it('require users to be authenticated to access InvoiceCancellation resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.invoicing.auth.login'));
})->with(['index']);

it('require users to have correct permissions to access InvoiceCancellation resource pages', function (string $method): void {
    actingAs(User::factory()->create());

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertForbidden();
})->with(['index']);

it('allows super admin users to access InvoiceCancellation resource pages', function (string $method): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with(['index']);

it('allows users with correct permissions to access InvoiceCancellation resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'InvoiceCancellation'));

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with(['index']);

it('displays InvoiceCancellation list page correctly', function (): void {
    $cancellations = InvoiceCancellation::factory()->count(3)->create([
        'invoice_id' => $this->invoice->id,
    ]);

    actingAs($this->createUserWithPermissionTo('view-any InvoiceCancellation'));

    livewire(ManageInvoiceCancellations::class)
        ->assertCanSeeTableRecords($cancellations);
});

test('creates InvoiceCancellation from modal action', function (): void {
    $user = $this->createUserWithPermissionsToActions(['create', 'view-any'], 'InvoiceCancellation');
    actingAs($user);

    livewire(ManageInvoiceCancellations::class)
        ->callTableAction('create', data: $this->form_data)
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas('invoice_cancellations', [
        'invoice_id' => $this->invoice->id,
        'cancelled_by' => $user->id,
        'reason' => $this->form_data['reason'],
    ]);
});

test('edits InvoiceCancellation from modal action', function (): void {
    $cancellation = InvoiceCancellation::factory()->create([
        'invoice_id' => $this->invoice->id,
        'cancelled_by' => $this->user->id,
    ]);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'InvoiceCancellation'));

    livewire(ManageInvoiceCancellations::class)
        ->callTableAction('edit', $cancellation->getKey(), [
            'invoice_id' => $this->invoice->id,
            'date' => now()->toDateString(),
            'reason' => 'Updated reason after review.',
        ])
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas('invoice_cancellations', [
        'id' => $cancellation->id,
        'reason' => 'Updated reason after review.',
    ]);
});

test('form validation requires invoice_id, date and reason', function (string $field): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'InvoiceCancellation'));

    livewire(ManageInvoiceCancellations::class)
        ->callTableAction('create', data: array_merge($this->form_data, [$field => '']))
        ->assertHasTableActionErrors([$field => 'required']);
})->with(['invoice_id', 'date', 'reason']);

test('form validation requires cancellation date to be equal or after invoice date', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'InvoiceCancellation'));

    $futureInvoice = Invoice::factory()->create([
        'date' => now()->addDay()->toDateString(),
        'items' => [['name' => 'Service', 'price' => 500.0, 'quantity' => 1]],
    ]);

    livewire(ManageInvoiceCancellations::class)
        ->callTableAction('create', data: array_merge($this->form_data, [
            'invoice_id' => $futureInvoice->id,
            'date' => now()->toDateString(),
        ]))
        ->assertHasTableActionErrors(['date']);
});

it('soft deletes an InvoiceCancellation', function (): void {
    $cancellation = InvoiceCancellation::factory()->create([
        'invoice_id' => $this->invoice->id,
    ]);

    actingAs($this->createUserWithPermissionsToActions(['delete', 'view-any'], 'InvoiceCancellation'));

    livewire(ManageInvoiceCancellations::class)
        ->callTableAction('delete', $cancellation->getKey())
        ->assertHasNoTableActionErrors();

    $this->assertSoftDeleted('invoice_cancellations', ['id' => $cancellation->id]);
});

it('restores a soft-deleted InvoiceCancellation', function (): void {
    $cancellation = InvoiceCancellation::factory()->create([
        'invoice_id' => $this->invoice->id,
    ]);
    $cancellation->delete();

    actingAs($this->createSuperAdminUser());

    livewire(ManageInvoiceCancellations::class)
        ->filterTable('trashed', 'with')
        ->callTableAction('restore', $cancellation->getKey())
        ->assertHasNoTableActionErrors();

    $this->assertNotSoftDeleted('invoice_cancellations', ['id' => $cancellation->id]);
});
