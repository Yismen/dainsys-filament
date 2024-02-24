<?php

namespace App\Filament\HumanResource\Resources\EmployeeResource\Pages;

use App\Filament\HumanResource\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
}
