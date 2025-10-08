<?php

use App\Enums\MaritalStatus;

test('values method return specific values', function () {
    expect(MaritalStatus::values())->toEqual([
        'Single',
        'Married',
        'Divorced',
        'Free Union',
    ]);
});

test('all method return associative array', function () {
    expect(MaritalStatus::toArray())->toEqual([
        'Single' => 'Single',
        'Married' => 'Married',
        'Divorced' => 'Divorced',
        'Free Union' => 'FreeUnion',
    ]);
});
