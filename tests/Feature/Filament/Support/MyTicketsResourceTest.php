<?php

use App\Enums\TicketPriorities;
use App\Events\TicketAssignedEvent;
use App\Events\TicketCompletedEvent;
use App\Events\TicketCreatedEvent;
use App\Events\TicketDeletedEvent;
use App\Events\TicketReopenedEvent;
use App\Events\TicketReplyCreatedEvent;
use App\Filament\Support\Resources\MyTickets\Pages\CreateMyTicket;
use App\Filament\Support\Resources\MyTickets\Pages\EditMyTicket;
use App\Filament\Support\Resources\MyTickets\Pages\ListMyTickets;
use App\Filament\Support\Resources\MyTickets\Pages\ViewMyTicket;
use App\Models\MyTicket;
use App\Models\Ticket;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    // Seed roles/permissions if applicable
    Filament::setCurrentPanel(
        Filament::getPanel('support'), // Where `app` is the ID of the panel you want to test.
    );
    Event::fake([
        TicketCreatedEvent::class,
        TicketAssignedEvent::class,
        TicketCompletedEvent::class,
        TicketDeletedEvent::class,
        TicketReplyCreatedEvent::class,
        TicketReopenedEvent::class,
    ]);

    $ticket = Ticket::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListMyTickets::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreateMyTicket::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditMyTicket::getRouteName(),
            'params' => ['record' => $ticket->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewMyTicket::getRouteName(),
            'params' => ['record' => $ticket->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];

    $this->form_data = [
        'subject' => 'new MyTicket',
        'description' => 'new ticket Description',
        // 'images' => [],
        'priority' => TicketPriorities::Normal->value,
    ];
});

it('require users to be authenticated to access MyTicket resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.support.auth.login'));
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('require users to have correct permissions to access MyTicket resource pages', function (string $method): void {
    actingAs(User::factory()->create());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));
    $response->assertNotFound();
})->with([
    // 'index',
    // 'create',
    'edit',
    'view',
]);

it('allow users with correct permissions to access MyTicket resource pages', function (string $method, string $component): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $ticket = Ticket::factory()->for($user, 'owner')->create();

    $response = get(route($component::getRouteName(),
        ['record' => $ticket->getKey()]));

    $response->assertOk();
})->with([
    ['edit', EditMyTicket::class],
    ['view', ViewMyTicket::class],
]);

it('prevent users from working tickets they dont own', function (string $method, string $component): void {
    $user = User::factory()->create();
    $another_user = User::factory()->create();

    $ticket = Ticket::factory()->for($another_user, 'owner')->create();
    $this->actingAs($user);

    $response = get(route($component::getRouteName(),
        ['record' => $ticket->getKey()]));

    $response->assertNotFound();
})->with([
    ['edit', EditMyTicket::class],
    ['view', ViewMyTicket::class],
]);

// it('displays MyTicket list page correctly with tickets created by the user', function () {
//     $user = User::factory()->create();
//     Ticket::factory()->for($user, 'owner')->create();

//     $this->actingAs($user);

//     $tickets = Ticket::get();

//     // dd($tickets->pluck('owner_id'), $user->id);

//     livewire(ListMyTickets::class)
//         ->assertCanSeeTableRecords($tickets);
// });

it('does not displays MyTicket list created by other users', function (): void {
    $user = User::factory()->create();
    $another_user = User::factory()->create();
    Ticket::factory()->for($another_user, 'owner')->create();
    $this->actingAs($user);

    $tickets = Ticket::get();

    livewire(ListMyTickets::class)
        ->assertCanNotSeeTableRecords($tickets);
});

test('table shows desired fields', function ($field): void {
    $user = User::factory()->create();
    actingAs($user);
    $ticket = Ticket::factory()->for($user, 'owner')->create();

    livewire(ListMyTickets::class)
        ->assertSee($ticket->$field);

})->with([
    'subject',
    // 'description',
]);

test('create MyTicket page works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'MyTicket'));

    livewire(CreateMyTicket::class)
        ->fillForm($this->form_data)
        ->call('create');

    $this->assertDatabaseHas('tickets', $this->form_data);
});

// test('edit MyTicket page works correctly', function () {
//     $ticket = Ticket::factory()->create();

//     actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'MyTicket'));

//     livewire(EditMyTicket::class, ['record' => $ticket->getKey()])
//         ->fillForm($this->form_data)
//         ->call('save')
//         ->assertHasNoErrors();

//     $this->assertDatabaseHas('tickets', array_merge(['id' => $ticket->id], $this->form_data));
// });

test('form validation require fields on create and edit pages', function (string $field): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'MyTicket'));

    // Test CreateMyTicket validation
    livewire(CreateMyTicket::class)
        ->fillForm([$field => ''])
        ->call('create')
        ->assertHasFormErrors([$field => 'required']);
    // Test EditMyTicket validation
    $ticket = Ticket::factory()->create();
    livewire(EditMyTicket::class, ['record' => $ticket->getKey()])
        ->fillForm([$field => ''])
        ->call('save')
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'subject',
    'description',
]);

it('autofocus the employee_id field on create and edit pages', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'MyTicket'));

    // Test CreateMyTicket autofocus
    livewire(CreateMyTicket::class)
        ->assertSeeHtml('autofocus');

    // Test EditMyTicket autofocus
    $ticket = Ticket::factory()->create();
    livewire(EditMyTicket::class, ['record' => $ticket->getKey()])
        ->assertSeeHtml('autofocus');
});
