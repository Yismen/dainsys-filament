<?php

use App\Enums\RevenueTypes;

test('values method return specific values', function () {
    expect(RevenueTypes::values())->toEqual([
        'downtime',
        'login time',
        'production time',
        'talk time',
        'conversions',
    ]);
});

test('all method return associative array', function () {
    expect(RevenueTypes::toArray())->toEqual([
        'downtime' => 'Downtime',
        'login time' => 'LoginTime',
        'production time' => 'ProductionTime',
        'talk time' => 'TalkTime',
        'conversions' => 'Conversions',
    ]);
});
