<?php

use App\Enums\TerminationTypes;

test('values method return specific values', function () {
    expect(TerminationTypes::values())->toEqual([
        'resignation',
        'termination',
        'firing',
        'abandonment',
        'dismissing',
    ]);
});

test('all method return associative array', function () {
    expect(TerminationTypes::toArray())->toEqual([
        'resignation' => 'Resignation',
        'termination' => 'Termination',
        'firing' => 'Firing',
        'abandonment' => 'Abandonment',
        'dismissing' => 'Dismissing',
    ]);
});
