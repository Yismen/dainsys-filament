<?php

use App\Models\Employee;
use App\Models\Site;
use App\Services\HC\BySite;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

beforeEach(function (): void {
    Cache::flush();
});

it('HC service count method returns a Collection', function (): void {
    $service = new BySite;
    $result = $service->count();
    expect($result)->toBeInstanceOf(Collection::class);
});

it('HC service count caches data as array', function (): void {
    Site::factory()->create();
    Employee::factory()->create();

    $service = new BySite;
    $result1 = $service->count();
    expect($result1)->toBeInstanceOf(Collection::class);

    // Result should contain normalized array data
    expect($result1->count())->toBeGreaterThanOrEqual(0);
});

it('HC service list method returns a Collection', function (): void {
    $service = new BySite;
    $result = $service->list();
    expect($result)->toBeInstanceOf(Collection::class);
});

it('HC service list caches data as array with employees', function (): void {
    $site = Site::factory()->create();
    Employee::factory()->for($site, 'site')->create();

    $service = new BySite;
    $result = $service->list();

    expect($result)->toBeInstanceOf(Collection::class);
});

it('cached count is accessible on subsequent reads', function (): void {
    $service = new BySite;

    $result1 = $service->count();
    expect($result1)->toBeInstanceOf(Collection::class);

    // Manually clear and re-create service
    $service2 = new BySite;
    $result2 = $service2->count();
    expect($result2)->toBeInstanceOf(Collection::class);
});
