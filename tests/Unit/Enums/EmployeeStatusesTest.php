<?php

use App\Enums\EmployeeStatuses;

test('names method return specific names', function () {
    expect(EmployeeStatuses::names())->toEqual([
        'Created',
        'Hired',
        'Suspended',
        'Terminated',
    ]);
});

test('values method return specific values', function () {
    expect(EmployeeStatuses::values())->toEqual([
        'Created',
        'Hired',
        'Suspended',
        'Terminated',
    ]);
});

test('all method return associative array', function () {
    expect(EmployeeStatuses::toArray())->toEqual([
        'Created' => 'Created',
        'Hired' => 'Hired',
        'Suspended' => 'Suspended',
        'Terminated' => 'Terminated',
    ]);
});
