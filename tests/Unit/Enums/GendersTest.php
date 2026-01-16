<?php

use App\Enums\Genders;

test('names method return specific names', function () {
    expect(Genders::names())->toEqual([
        'Male',
        'Female',
    ]);
});

test('values method return specific values', function () {
    expect(Genders::values())->toEqual([
        'Male',
        'Female',
        // 'Undefined',
    ]);
});

test('all method return associative array', function () {
    expect(Genders::toArray())->toEqual([
        'Male' => 'Male',
        'Female' => 'Female',
        // 'Undefined' => 'Undefined',
    ]);
});
