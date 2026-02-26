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

it('can render the my tickets page', function () {
    $user = User::factory()->create();
    actingAs($user)
        ->get(route('my-tickets-management'))
        ->assertSuccessful()
        ->assertSeeLivewire('my-tickets-management');
});
