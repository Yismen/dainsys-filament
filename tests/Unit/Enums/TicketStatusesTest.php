<?php

use App\Enums\TicketStatuses;

test('values method return specific values', function () {
    expect(TicketStatuses::values())->toEqual([
        1,
        2,
        3,
        4,
        5,
        6,
    ]);
});

test('all method return associative array', function () {
    expect(TicketStatuses::toArray())->toEqual([
        1 => 'Pending',
        2 => 'PendingExpired',
        3 => 'InProgress',
        4 => 'InProgressExpired',
        5 => 'Completed',
        6 => 'CompletedExpired',
    ]);
});
