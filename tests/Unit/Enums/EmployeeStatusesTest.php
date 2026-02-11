<?php

use App\Enums\EmployeeStatuses;

test('names method return specific names', function (): void {
    expect(EmployeeStatuses::names())->toEqual([
        'Created',
        'Hired',
        'Suspended',
        'Terminated',
    ]);
});

test('values method return specific values', function (): void {
    expect(EmployeeStatuses::values())->toEqual([
        'Created',
        'Hired',
        'Suspended',
        'Terminated',
    ]);
});

test('all method return associative array', function (): void {
    expect(EmployeeStatuses::toArray())->toEqual([
        'Created' => 'Created',
        'Hired' => 'Hired',
        'Suspended' => 'Suspended',
        'Terminated' => 'Terminated',
    ]);
});
