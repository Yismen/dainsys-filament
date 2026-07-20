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
        'not assigned' => 'Not Assigned',
        'expired before assignment' => 'Expired Before Assignment',
        'assigned to user' => 'Assigned to User',
        'expired and assigned' => 'Expired and Assigned',
        'completed in time' => 'Completed in Time',
        'completed after expiring' => 'Completed After Expiring',
    ]);
});
