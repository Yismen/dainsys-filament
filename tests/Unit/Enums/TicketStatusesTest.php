<?php

use App\Enums\TicketStatuses;

test('values method return specific names', function (): void {
    expect(TicketStatuses::names())->toEqual([
        'Pending',
        'PendingExpired',
        'InProgress',
        'InProgressExpired',
        'Completed',
        'CompletedExpired',
    ]);
});

test('values method return specific values', function (): void {
    expect(TicketStatuses::values())->toEqual([
        'not assigned',
        'expired before assignment',
        'assigned to user',
        'expired and assigned',
        'completed in time',
        'completed after expiring',
    ]);
});

test('all method return associative array', function (): void {
    expect(TicketStatuses::toArray())->toEqual([
        'not assigned' => 'Pending',
        'expired before assignment' => 'PendingExpired',
        'assigned to user' => 'InProgress',
        'expired and assigned' => 'InProgressExpired',
        'completed in time' => 'Completed',
        'completed after expiring' => 'CompletedExpired',
    ]);
});
