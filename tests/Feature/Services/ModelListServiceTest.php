<?php

use App\Models\User;
use App\Services\ModelListService;
use Illuminate\Support\Facades\Cache;

it('returns correct results', function (): void {
    User::factory(3)->create(0);

    $reflectionMethod = new \ReflectionMethod(ModelListService::class, 'get');

    $result = $reflectionMethod->invoke(null, User::query());

    expect($result)
        ->tobe(User::query()->orderBy('name')->pluck('name', 'id')->toArray());
});

it('store the correct key in the cache system and return the correct values for the cache', function (): void {
    User::factory(3)->create(0);

    $reflectionMethod = new \ReflectionMethod(ModelListService::class, 'get');

    $result = $reflectionMethod->invoke(null, User::query());

    $key = \implode('_', [
        'model_list',
        str(User::query()->select('name', 'id')->orderBy('name')->toRawSql())->snake(),
    ]);

    expect(Cache::has($key))
        ->tobe(true);

    expect(Cache::get($key))
        ->tobe(User::query()->orderBy('name')->pluck('name', 'id')->toArray());
});
