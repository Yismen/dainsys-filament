<?php

use App\Filament\Recruitment\Resources\Employees\EmployeeResource;

test('recruitment employee resource is view only', function (): void {
    $pages = EmployeeResource::getPages();

    expect($pages)
        ->toHaveKey('index')
        ->not->toHaveKeys(['view', 'create', 'edit']);
});
