<?php

use App\Enums\PersonalIdTypes;

test('values method return specific values', function (): void {
    expect(PersonalIdTypes::values())->toEqual([
        'dominican id',
        'passport',
        // 'Undefined',
    ]);
});

test('all method return associative array', function (): void {
    expect(PersonalIdTypes::toArray())->toEqual([
        'dominican id' => 'Dominican ID',
        'passport' => 'Passport',
        // 'Undefined' => 'Undefined',
    ]);
});
