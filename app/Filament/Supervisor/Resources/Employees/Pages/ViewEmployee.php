<?php

namespace App\Filament\Supervisor\Resources\Employees\Pages;

use App\Filament\Supervisor\Resources\Employees\EmployeeResource;
use App\Filament\Supervisor\Resources\Employees\Schemas\EmployeeInfolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewEmployee extends ViewRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function infolist(Schema $schema): Schema
    {
        return EmployeeInfolist::configure($schema);
    }
}
