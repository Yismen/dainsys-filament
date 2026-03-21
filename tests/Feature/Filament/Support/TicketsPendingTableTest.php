<?php

use App\Enums\SupportRoles;
use App\Filament\Support\Widgets\TicketsPendingTable;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

it('refreshes mounted ticket replies in the pending tickets view modal', function (): void {
    /** @var User $manager */
    $manager = User::factory()->createOne();

    Role::firstOrCreate(['name' => SupportRoles::Manager->value, 'guard_name' => 'web']);
    $manager->assignRole(SupportRoles::Manager->value);

    $ticket = Ticket::factory()->create();

    actingAs($manager);

    $component = livewire(TicketsPendingTable::class)
        ->mountTableAction('view', $ticket);

    expect($component->getMountedActionModalHtml())
        ->not->toContain('Fresh reply from the support manager.');

    TicketReply::factory()->createQuietly([
        'ticket_id' => $ticket->id,
        'user_id' => $manager->id,
        'content' => 'Fresh reply from the support manager.',
    ]);

    $component
        ->call('ticketRepliesUpdated', (string) $ticket->getKey());

    expect($component->getMountedActionModalHtml())
        ->toContain('Fresh reply from the support manager.');
});
