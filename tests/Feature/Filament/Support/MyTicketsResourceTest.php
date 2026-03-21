<?php

use App\Livewire\MyTicketsManagement;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

it('can render the my tickets page', function (): void {
    /** @var User $user */
    $user = User::factory()->createOne();

    actingAs($user)
        ->get(route('my-tickets-management'))
        ->assertSuccessful()
        ->assertSeeLivewire('my-tickets-management');
});

it('refreshes mounted ticket replies in the my tickets view modal', function (): void {
    /** @var User $user */
    $user = User::factory()->createOne();
    $ticket = Ticket::factory()->create([
        'owner_id' => $user->id,
    ]);

    actingAs($user);

    $component = livewire(MyTicketsManagement::class)
        ->mountTableAction('view', $ticket);

    expect($component->getMountedActionModalHtml())
        ->not->toContain('Fresh reply from the ticket owner.');

    TicketReply::factory()->createQuietly([
        'ticket_id' => $ticket->id,
        'user_id' => $user->id,
        'content' => 'Fresh reply from the ticket owner.',
    ]);

    $component
        ->call('ticketRepliesUpdated', (string) $ticket->getKey());

    expect($component->getMountedActionModalHtml())
        ->toContain('Fresh reply from the ticket owner.');
});
