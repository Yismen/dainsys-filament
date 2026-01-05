<?php

use App\Enums\SuspensionStatuses;

test('values method return specific values', function () {
    expect(SuspensionStatuses::values())->toEqual([
        'Pending',
        'Current',
        'Completed',
    ]);
});

test('all method return associative array', function () {
    expect(SuspensionStatuses::toArray())->toEqual([
        'Pending' => 'Pending',
        'Current' => 'Current',
        'Completed' => 'Completed',
    ]);
});
