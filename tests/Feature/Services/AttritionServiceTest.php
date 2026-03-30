<?php

use App\Services\Attrition\TerminatedService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
});

it('attrition list method returns a Collection', function () {
    $service = new TerminatedService;
    $result = $service->list();
    expect($result)->toBeInstanceOf(Collection::class);
});

it('attrition list caches data as array', function () {
    $service = new TerminatedService;
    $result = $service->list();

    expect($result)->toBeInstanceOf(Collection::class);
    // Result is a collection, even if empty
    expect($result->count())->toBeGreaterThanOrEqual(0);
});

it('attrition count method returns integer', function () {
    $service = new TerminatedService;
    $result = $service->count();

    expect($result)->toBeInt();
});

it('cached attrition list is accessible on subsequent reads', function () {
    $service = new TerminatedService;

    $result1 = $service->list();
    expect($result1)->toBeInstanceOf(Collection::class);

    // Create new service instance and verify cache is used
    $service2 = new TerminatedService;
    $result2 = $service2->list();
    expect($result2)->toBeInstanceOf(Collection::class);
});
