<?php

use App\Models\User;
use App\Models\Ticket;
use Livewire\Livewire;
use Illuminate\Support\Arr;
use Filament\Facades\Filament;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Cache;
use Filament\Resources\Pages\EditRecord;
use Spatie\Permission\Models\Permission;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Support\Resources\TicketResource;
use App\Filament\Support\Resources\TicketResource\Pages\EditTicket;
use App\Filament\Support\Resources\TicketResource\Pages\ViewTicket;
use App\Filament\Support\Resources\TicketResource\Pages\ListTickets;
use App\Filament\Support\Resources\TicketResource\Pages\CreateTicket;




uses(\Illuminate\Foundation\Testing\WithFaker::class);

beforeEach(function () {
    // Seed roles/permissions if applicable
    Filament::setCurrentPanel(
        Filament::getPanel('support'), // Where `app` is the ID of the panel you want to test.
    );
});

test('regular tickets are forbiden', function () {
    $tickets = Ticket::factory()->count(10)->create();

    $this->actingAs(User::factory()->create())
        ->get(TicketResource::getUrl('index'))
        ->assertForbidden();
});

test('regular users are forbiden to create tickets', function () {
    $this->actingAs(User::factory()->create())
        ->get(TicketResource::getUrl('create'))
        ->assertForbidden();
});
function regular_users_are_forbiden_to_store_tickets()
{
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateTicket::class)
        ->assertForbidden();
}
function regular_users_are_forbiden_to_update_tickets()
{
    $ticket = Ticket::factory()->create();

    $this->actingAs(User::factory()->create());

    Livewire::test(EditTicket::class, ['record' => $ticket->getKey()])
        ->assertForbidden();
}

test('regular users are forbiden to edit tickets', function () {
    $ticket = Ticket::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(TicketResource::getUrl('edit', ['record' => $ticket->getKey()]))
        ->assertForbidden();
});

test('regular users are forbiden to view tickets', function () {
    $ticket = Ticket::factory()->create();

    $this->actingAs(User::factory()->create());

    Livewire::test(ViewTicket::class, ['record' => $ticket->getKey()])
        ->assertForbidden();
});

test('tickets index page displays correctly', function () {
    $tickets = Ticket::factory()->count(10)->create();

    $this->actingAs($this->createUserWithPermissionTo('view-any Ticket'))
        ->get(TicketResource::getUrl('index'))
        ->assertStatus(200)
        ->assertSee($tickets->first()->name);
});

test('can create a ticket', function () {
    $data = Arr::except(Ticket::factory()->make()->toArray(), [
        'reference',
        'images',
        'expected_at',
        'assigned_to',
        'assigned_at',
        'completed_at',
    ]);

    $this->actingAs($this->createUserWithPermissionsToActions(
        ['view-any', 'create'],
        'Ticket'
    ));

    Livewire::test(CreateTicket::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasNoErrors();

    $this->assertDatabaseHas(Ticket::class, $data);
});

test('can edit a ticket', function () {
    $ticket = Ticket::factory()->create();
    $data = Arr::except(Ticket::factory()->make()->toArray(), [
        'reference',
        'images',
        'expected_at',
        'assigned_to',
        'assigned_at',
        'completed_at',
    ]);

    $this->actingAs($this->createUserWithPermissionsToActions(
        ['view-any', 'update'],
        'Ticket'
    ));

    Livewire::test(EditTicket::class, ['record' => $ticket->getKey()])
        ->fillForm($data)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas(Ticket::class, ['id' => $ticket->id, 'subject' => $data['subject']]);
});

/** @test */
// public function can_delete_a_ticket()
// {
//     $ticket = Ticket::factory()->create();
//     $data = [
//         'name' => $this->faker->name(),
//         'description' => $this->faker->paragraph(),
//     ];
//     $this->actingAs($this->createUserWithPermissionsToActions(
//         ['view-any', 'update'],
//         'Ticket'
//     ));
//     Livewire::test(EditTicket::class, ['record' => $ticket->getKey()])
//         ->callAction(DeleteAction::class);
//     $this->assertSoftDeleted(Ticket::class, ['id' => $ticket->id]);
// }
