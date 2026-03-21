<?php

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Carbon\Carbon;

use function Pest\Laravel\actingAs;

it('renders ticket replies with sender based styles and humanized timestamps', function (): void {
    Carbon::setTestNow('2026-03-20 12:00:00');

    $viewer = User::factory()->create();
    $otherUser = User::factory()->create();

    $ticket = Ticket::factory()->create([
        'owner_id' => $viewer->id,
    ]);

    TicketReply::factory()->create([
        'ticket_id' => $ticket->id,
        'user_id' => $viewer->id,
        'content' => 'I already checked this issue from my side.',
        'created_at' => now()->subHour(),
    ]);

    TicketReply::factory()->create([
        'ticket_id' => $ticket->id,
        'user_id' => $otherUser->id,
        'content' => 'Thanks, I will investigate and update soon.',
        'created_at' => now()->subHours(2),
    ]);

    actingAs($viewer);

    $html = view('filament.support.tickets.reply-infolist', [
        'record' => $ticket->load('replies.user'),
    ])->render();

    expect($html)
        ->toContain(e($viewer->name))
        ->toContain(e($otherUser->name))
        ->toContain('I already checked this issue from my side.')
        ->toContain('Thanks, I will investigate and update soon.')
        ->toContain('data-owner="self"')
        ->toContain('data-owner="other"')
        ->toContain('ml-8')
        ->toContain('text-xs text-gray-500')
        ->toContain('1 hour ago')
        ->toContain('2 hours ago');

    expect(substr_count($html, 'data-owner="self"'))->toBe(1)
        ->and(substr_count($html, 'data-owner="other"'))->toBe(1);

    Carbon::setTestNow();
});
