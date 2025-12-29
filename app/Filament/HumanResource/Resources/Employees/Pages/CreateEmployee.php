<?php

namespace App\Filament\HumanResource\Resources\Employees\Pages;

use App\Filament\HumanResource\Resources\Employees\EmployeeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
}
