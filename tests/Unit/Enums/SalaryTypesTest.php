<?php

use App\Enums\SalaryTypes;

test('values method return specific values', function (): void {
    expect(SalaryTypes::values())->toEqual([
        'salary',
        'hourly',
        'by sales',
    ]);
});

test('all method return associative array', function (): void {
    expect(SalaryTypes::toArray())->toEqual([
        'salary' => 'Salary',
        'hourly' => 'Hourly',
        'by sales' => 'BySales',
    ]);
});
