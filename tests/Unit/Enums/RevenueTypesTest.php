<?php

use App\Enums\RevenueTypes;

test('values method return specific values', function () {
    expect(RevenueTypes::values())->toEqual([
        'Login Time',
        'Production Time',
        'Talk Time',
        'Sales',
    ]);
});

test('all method return associative array', function () {
    expect(RevenueTypes::toArray())->toEqual([
        'Login Time' => 'LoginTime',
        'Production Time' => 'ProductionTime',
        'Talk Time' => 'TalkTime',
        'Sales' => 'Sales',
    ]);
});
