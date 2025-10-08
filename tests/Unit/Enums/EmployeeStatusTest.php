<?php

use App\Enums\EmployeeStatus;

test('values method return specific values', function () {
    expect(EmployeeStatus::values())->toEqual([
        'Current',
        'Inactive',
        'Suspended',
    ]);
});

test('all method return associative array', function () {
    expect(EmployeeStatus::toArray())->toEqual([
        'Current' => 'Current',
        'Inactive' => 'Inactive',
        'Suspended' => 'Suspended',
    ]);
});
