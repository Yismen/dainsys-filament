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
        'male',
        'female',
        // 'undefined',
    ]);
});

test('all method return associative array', function () {
    expect(Genders::toArray())->toEqual([
        'male' => 'Male',
        'female' => 'Female',
        // 'Undefined' => 'Undefined',
    ]);
});
