<?php

use App\Enums\Gender;

test('values method return specific values', function () {
    expect(Gender::values())->toEqual([
        'Male',
        'Female',
        // 'Undefined',
    ]);
});

test('all method return associative array', function () {
    expect(Gender::toArray())->toEqual([
        'Male' => 'Male',
        'Female' => 'Female',
        // 'Undefined' => 'Undefined',
    ]);
});
