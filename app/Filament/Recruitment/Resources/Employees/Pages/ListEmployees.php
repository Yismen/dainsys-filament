<?php

namespace App\Filament\Recruitment\Resources\Employees\Pages;

use App\Filament\Recruitment\Resources\Employees\EmployeeResource;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
