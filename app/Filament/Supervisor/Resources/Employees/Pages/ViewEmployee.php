<?php

namespace App\Filament\Supervisor\Resources\Employees\Pages;

use App\Filament\Supervisor\Resources\Employees\EmployeeResource;
use App\Filament\Supervisor\Resources\Employees\Schemas\EmployeeInfolist;
use Filament\Resources\Pages\ViewRecord;

class ViewEmployee extends ViewRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function infolist(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return EmployeeInfolist::configure($schema);
    }
}
