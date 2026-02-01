<?php

use App\Filament\Admin\Pages\AdminDashboard;

test('admin dashboard component exists', function () {
    $dashboard = new AdminDashboard;

    expect($dashboard)->toBeInstanceOf(AdminDashboard::class);
});

test('dashboard has correct widget count', function () {
    $dashboard = new AdminDashboard;
    $widgets = $dashboard->getWidgets();

    expect(count($widgets))->toBe(7);
});

test('dashboard uses two column grid', function () {
    $dashboard = new AdminDashboard;
    $columns = $dashboard->getColumns();

    expect($columns)->toBe([
        'md' => 2,
        'lg' => 2,
    ]);
});
