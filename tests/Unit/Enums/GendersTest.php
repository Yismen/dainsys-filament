<?php

use App\Enums\Genders;

test('names method return specific names', function (): void {
    expect(Genders::names())->toEqual([
        'Male',
        'Female',
    ]);
});

test('values method return specific values', function (): void {
    expect(Genders::values())->toEqual([
        'Male',
        'Female',
        // 'undefined',
    ]);
});

test('all method return associative array', function (): void {
    expect(Genders::toArray())->toEqual([
        'Male' => 'Male',
        'Female' => 'Female',
        // 'Undefined' => 'Undefined',
    ]);
});
