<?php

use App\Filament\Invoicing\Resources\InvoicePayments\Pages\ManageInvoicePayments;
use App\Models\Invoice;
use App\Models\InvoicePayment;
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

    $this->resource_routes = [
        'index' => [
            'route' => ManageInvoicePayments::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
    ];

    $this->form_data = [
        'invoice_id' => $this->invoice->id,
        'amount' => 100.00,
        'date' => now()->toDateString(),
        'reference' => 'REF-001',
        'description' => 'Partial payment for invoice.',
    ];
});

it('require users to be authenticated to access InvoicePayment resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.invoicing.auth.login'));
})->with(['index']);

it('require users to have correct permissions to access InvoicePayment resource pages', function (string $method): void {
    actingAs(User::factory()->create());

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertForbidden();
})->with(['index']);

it('allows super admin users to access InvoicePayment resource pages', function (string $method): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with(['index']);

it('allow users with correct permissions to access InvoicePayment resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'InvoicePayment'));

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with(['index']);

it('displays InvoicePayment list page correctly', function (): void {
    $payments = InvoicePayment::factory()->count(3)->create(['invoice_id' => $this->invoice->id]);

    actingAs($this->createUserWithPermissionTo('view-any InvoicePayment'));

    livewire(ManageInvoicePayments::class)
        ->assertCanSeeTableRecords($payments);
});

test('creates InvoicePayment from modal action', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'InvoicePayment'));

    livewire(ManageInvoicePayments::class)
        ->callAction('create', data: $this->form_data)
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas('invoice_payments', [
        'invoice_id' => $this->invoice->id,
        'amount' => 100.00,
        'reference' => 'REF-001',
    ]);
});

test('edits InvoicePayment from modal action', function (): void {
    $payment = InvoicePayment::factory()->create([
        'invoice_id' => $this->invoice->id,
        'amount' => 50.00,
    ]);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'InvoicePayment'));

    livewire(ManageInvoicePayments::class)
        ->callAction('edit', $payment->getKey(), [
            'invoice_id' => $this->invoice->id,
            'amount' => 75.00,
            'date' => now()->toDateString(),
            'reference' => 'REF-UPDATED',
        ])
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas('invoice_payments', [
        'id' => $payment->id,
        'amount' => 75.00,
        'reference' => 'REF-UPDATED',
    ]);
});

test('form validation requires invoice_id, amount and date', function (string $field): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'InvoicePayment'));

    livewire(ManageInvoicePayments::class)
        ->callAction('create', data: array_merge($this->form_data, [$field => '']))
        ->assertHasTableActionErrors([$field => 'required']);
})->with(['invoice_id', 'amount', 'date']);

it('soft deletes an InvoicePayment', function (): void {
    $payment = InvoicePayment::factory()->create(['invoice_id' => $this->invoice->id]);

    actingAs($this->createUserWithPermissionsToActions(['delete', 'view-any'], 'InvoicePayment'));

    livewire(ManageInvoicePayments::class)
        ->callAction('delete', $payment->getKey())
        ->assertHasNoTableActionErrors();

    $this->assertSoftDeleted('invoice_payments', ['id' => $payment->id]);
});

it('restores a soft-deleted InvoicePayment', function (): void {
    $payment = InvoicePayment::factory()->create(['invoice_id' => $this->invoice->id]);
    $payment->delete();

    actingAs($this->createSuperAdminUser());

    livewire(ManageInvoicePayments::class)
        ->filterTable('trashed', 'with')
        ->callAction('restore', $payment->getKey())
        ->assertHasNoTableActionErrors();

    $this->assertNotSoftDeleted('invoice_payments', ['id' => $payment->id]);
});
