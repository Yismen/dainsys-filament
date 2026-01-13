<?php

use App\Enums\TicketPriorities;
use App\Events\TicketAssignedEvent;
use App\Events\TicketCompletedEvent;
use App\Events\TicketCreatedEvent;
use App\Events\TicketDeletedEvent;
use App\Events\TicketReopenedEvent;
use App\Events\TicketReplyCreatedEvent;
use App\Filament\Support\Resources\Tickets\Pages\CreateTicket;
use App\Filament\Support\Resources\Tickets\Pages\EditTicket;
use App\Filament\Support\Resources\Tickets\Pages\ListTickets;
use App\Filament\Support\Resources\Tickets\Pages\ViewTicket;
use App\Models\Ticket;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
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
            'route' => ListTickets::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreateTicket::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditTicket::getRouteName(),
            'params' => ['record' => $ticket->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewTicket::getRouteName(),
            'params' => ['record' => $ticket->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];

    $this->form_data = [
        'subject' => 'new Ticket',
        'description' => 'new ticket Description',
        // 'images' => [],
        'priority' => TicketPriorities::Normal->value,
    ];
});

it('require users to be authenticated to access Ticket resource pages', function (string $method) {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.support.auth.login'));
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('require users to have correct permissions to access Ticket resource pages', function (string $method) {
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

it('allow users with correct permissions to access Ticket resource pages', function (string $method, string $component) {
    $user = User::factory()->create();
    $this->actingAs($user);

    $ticket = Ticket::factory()->for($user, 'owner')->create();

    $response = get(route($component::getRouteName(),
        ['record' => $ticket->getKey()]));

    $response->assertOk();
})->with([
    ['edit', EditTicket::class],
    ['view', ViewTicket::class],
]);

it('prevent users from working tickets they dont own', function (string $method, string $component) {
    $user = User::factory()->create();
    $another_user = User::factory()->create();

    $ticket = Ticket::factory()->for($another_user, 'owner')->create();
    $this->actingAs($user);

    $response = get(route($component::getRouteName(),
        ['record' => $ticket->getKey()]));

    $response->assertNotFound();
})->with([
    ['edit', EditTicket::class],
    ['view', ViewTicket::class],
]);

// it('displays Ticket list page correctly with tickets created by the user', function () {
//     $user = User::factory()->create();
//     Ticket::factory()->for($user, 'owner')->create();

//     $this->actingAs($user);

//     $tickets = Ticket::get();

//     // dd($tickets->pluck('owner_id'), $user->id);

//     livewire(ListTickets::class)
//         ->assertCanSeeTableRecords($tickets);
// });

it('does not displays Ticket list created by other users', function () {
    $user = User::factory()->create();
    $another_user = User::factory()->create();
    Ticket::factory()->for($another_user, 'owner')->create();
    $this->actingAs($user);

    $tickets = Ticket::get();

    livewire(ListTickets::class)
        ->assertCanNotSeeTableRecords($tickets);
});

test('table shows desired fields', function ($field) {
    $user = User::factory()->create();
    actingAs($user);
    $ticket = Ticket::factory()->for($user, 'owner')->create();

    livewire(ListTickets::class)
        ->assertSee($ticket->$field);

})->with([
    'subject',
    // 'description',
]);

test('create Ticket page works correctly', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Ticket'));

    livewire(CreateTicket::class)
        ->fillForm($this->form_data)
        ->call('create');

    $this->assertDatabaseHas('tickets', $this->form_data);
});

// test('edit Ticket page works correctly', function () {
//     $ticket = Ticket::factory()->create();

//     actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Ticket'));

//     livewire(EditTicket::class, ['record' => $ticket->getKey()])
//         ->fillForm($this->form_data)
//         ->call('save')
//         ->assertHasNoErrors();

//     $this->assertDatabaseHas('tickets', array_merge(['id' => $ticket->id], $this->form_data));
// });

test('form validation require fields on create and edit pages', function (string $field) {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Ticket'));

    // Test CreateTicket validation
    livewire(CreateTicket::class)
        ->fillForm([$field => ''])
        ->call('create')
        ->assertHasFormErrors([$field => 'required']);
    // Test EditTicket validation
    $ticket = Ticket::factory()->create();
    livewire(EditTicket::class, ['record' => $ticket->getKey()])
        ->fillForm([$field => ''])
        ->call('save')
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'subject',
    'description',
]);

it('autofocus the employee_id field on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Ticket'));

    // Test CreateTicket autofocus
    livewire(CreateTicket::class)
        ->assertSeeHtml('autofocus');

    // Test EditTicket autofocus
    $ticket = Ticket::factory()->create();
    livewire(EditTicket::class, ['record' => $ticket->getKey()])
        ->assertSeeHtml('autofocus');
});
