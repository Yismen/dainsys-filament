<?php

use App\Enums\SuspensionStatuses;

test('values method return specific values', function (): void {
    expect(SuspensionStatuses::values())->toEqual([
        'Pending',
        'Current',
        'Completed',
    ]);
});

test('all method return associative array', function (): void {
    expect(SuspensionStatuses::toArray())->toEqual([
        'Pending' => 'Pending',
        'Current' => 'Current',
        'Completed' => 'Completed',
    ]);
});
