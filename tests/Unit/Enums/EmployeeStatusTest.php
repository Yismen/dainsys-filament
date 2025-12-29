<?php

use App\Enums\EmployeeStatus;

test('values method return specific values', function () {
    expect(EmployeeStatus::values())->toEqual([
        'Created',
        'Hired',
        'Suspended',
        'Terminated',
    ]);
});

test('all method return associative array', function () {
    expect(EmployeeStatus::toArray())->toEqual([
        'Created' => 'Created',
        'Hired' => 'Hired',
        'Suspended' => 'Suspended',
        'Terminated' => 'Terminated',
    ]);
});
