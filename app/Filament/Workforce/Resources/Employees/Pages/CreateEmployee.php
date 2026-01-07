<?php

namespace App\Filament\Workforce\Resources\Employees\Pages;

use App\Filament\Workforce\Resources\Employees\EmployeeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
}
