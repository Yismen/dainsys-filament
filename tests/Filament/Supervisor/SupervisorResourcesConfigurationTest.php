<?php

use App\Filament\Supervisor\Resources\PayrollHours\PayrollHourResource;
use App\Filament\Supervisor\Resources\NightlyHours\NightlyHourResource;
use App\Filament\Supervisor\Resources\Productions\ProductionResource;
use App\Filament\Supervisor\Resources\EmployeeMetrics\EmployeeMetricsResource;

test('payroll hour resource is properly configured', function () {
    $resource = new PayrollHourResource();
    expect($resource)->toBeInstanceOf(PayrollHourResource::class);
    expect(PayrollHourResource::getPages())->toHaveKey('index');
    expect(PayrollHourResource::getPages())->not->toHaveKey('create');
    expect(PayrollHourResource::getPages())->not->toHaveKey('edit');
});

test('nightly hour resource is properly configured', function () {
    $resource = new NightlyHourResource();
    expect($resource)->toBeInstanceOf(NightlyHourResource::class);
    expect(NightlyHourResource::getPages())->toHaveKey('index');
    expect(NightlyHourResource::getPages())->not->toHaveKey('create');
    expect(NightlyHourResource::getPages())->not->toHaveKey('edit');
});

test('production resource is properly configured', function () {
    $resource = new ProductionResource();
    expect($resource)->toBeInstanceOf(ProductionResource::class);
    expect(ProductionResource::getPages())->toHaveKey('index');
    expect(ProductionResource::getPages())->not->toHaveKey('create');
    expect(ProductionResource::getPages())->not->toHaveKey('edit');
});

test('employee metrics resource is properly configured', function () {
    $resource = new EmployeeMetricsResource();
    expect($resource)->toBeInstanceOf(EmployeeMetricsResource::class);
    expect(EmployeeMetricsResource::getPages())->toHaveKey('index');
    expect(EmployeeMetricsResource::getPages())->not->toHaveKey('create');
    expect(EmployeeMetricsResource::getPages())->not->toHaveKey('edit');
});
