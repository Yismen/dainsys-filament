<?php

use App\Models\Applicant;

test('applicant factory uses a single name field', function () {
    $attributes = Applicant::factory()->make()->getAttributes();

    expect($attributes)
        ->toHaveKey('name')
        ->not->toHaveKey('first_name')
        ->not->toHaveKey('last_name');
});
