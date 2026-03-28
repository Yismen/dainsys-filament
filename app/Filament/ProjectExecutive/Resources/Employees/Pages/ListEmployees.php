<?php

namespace App\Filament\ProjectExecutive\Resources\Employees\Pages;

use App\Filament\ProjectExecutive\Resources\Employees\EmployeeResource;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;
}
