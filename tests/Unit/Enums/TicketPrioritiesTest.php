<?php

use App\Enums\TicketPriorities;

test('values method return specific values', function (): void {
    expect(TicketPriorities::values())->toEqual([
        1,
        2,
        3,
        4,
    ]);
});

test('all method return associative array', function (): void {
    expect(TicketPriorities::toArray())->toEqual([
        1 => 'Normal',
        2 => 'Medium',
        3 => 'High',
        4 => 'Emergency',
    ]);
});
