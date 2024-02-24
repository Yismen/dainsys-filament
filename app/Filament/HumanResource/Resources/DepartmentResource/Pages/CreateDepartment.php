<?php

namespace App\Filament\HumanResource\Resources\DepartmentResource\Pages;

use App\Filament\HumanResource\Resources\DepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDepartment extends CreateRecord
{
    protected static string $resource = DepartmentResource::class;
}
