<?php

namespace App\Filament\Support\Resources;

use Tests\TestCase;
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
use Illuminate\Foundation\Testing\WithFaker;
use App\Filament\Support\Resources\TicketResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filament\Support\Resources\TicketResource\Pages\EditTicket;
use App\Filament\Support\Resources\TicketResource\Pages\ViewTicket;
use App\Filament\Support\Resources\TicketResource\Pages\ListTickets;
use App\Filament\Support\Resources\TicketResource\Pages\CreateTicket;

class TicketResourceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles/permissions if applicable
        Filament::setCurrentPanel(
            Filament::getPanel('support'), // Where `app` is the ID of the panel you want to test.
        );
    }

    // Guests

    /** @test */
    public function regular_tickets_are_forbiden()
    {
        $tickets = Ticket::factory()->count(10)->create();

        $this->actingAs(User::factory()->create())
            ->get(TicketResource::getUrl('index'))
            ->assertForbidden();
    }
    /** @test */
    public function regular_users_are_forbiden_to_create_tickets()
    {
        $this->actingAs(User::factory()->create())
            ->get(TicketResource::getUrl('create'))
            ->assertForbidden();
    }
    public function regular_users_are_forbiden_to_store_tickets()
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateTicket::class)
            ->assertForbidden();
    }
    public function regular_users_are_forbiden_to_update_tickets()
    {
        $ticket = Ticket::factory()->create();

        $this->actingAs(User::factory()->create());

        Livewire::test(EditTicket::class, ['record' => $ticket->getKey()])
            ->assertForbidden();
    }

    /** @test */
    public function regular_users_are_forbiden_to_edit_tickets()
    {
        $ticket = Ticket::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get(TicketResource::getUrl('edit', ['record' => $ticket->getKey()]))
            ->assertForbidden();
    }

    /** @test */
    public function regular_users_are_forbiden_to_view_tickets()
    {
        $ticket = Ticket::factory()->create();

        $this->actingAs(User::factory()->create());

        Livewire::test(ViewTicket::class, ['record' => $ticket->getKey()])
            ->assertForbidden();
    }

    // Authorized

    /** @test */
    public function tickets_index_page_displays_correctly()
    {
        $tickets = Ticket::factory()->count(10)->create();

        $this->actingAs($this->createUserWithPermissionTo('view-any Ticket'))
            ->get(TicketResource::getUrl('index'))
            ->assertStatus(200)
            ->assertSee($tickets->first()->name);
    }

    /** @test */
    public function can_create_a_ticket()
    {
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
    }



    /** @test */
    public function can_edit_a_ticket()
    {
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
    }

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
}
