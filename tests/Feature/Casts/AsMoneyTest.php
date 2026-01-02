<?php

use App\Casts\AsMoney;
use App\Models\User;

it('get value as money', function () {
    $asMoney = new AsMoney;

    expect($asMoney->get(new User, 'id', 15000, []))->toBe(150.0);
});

it('set value as money', function () {
    $asMoney = new AsMoney;

    expect($asMoney->set(new User, 'id', 150, []))->toBe(15000.0);
});
