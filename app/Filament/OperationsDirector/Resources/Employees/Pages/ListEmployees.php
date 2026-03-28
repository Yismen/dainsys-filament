<?php

namespace App\Filament\OperationsDirector\Resources\Employees\Pages;

use App\Filament\OperationsDirector\Resources\Employees\EmployeeResource;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;
}
