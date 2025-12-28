<?php

namespace App\Filament\HumanResource\Resources\Departments\Pages;

use App\Filament\HumanResource\Resources\Departments\DepartmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDepartment extends CreateRecord
{
    protected static string $resource = DepartmentResource::class;
}
