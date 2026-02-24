<?php

namespace App\Filament\Workforce\Resources\Employees\Schemas;

use App\Schemas\Filament\Workforce\EmployeeSchema;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(
                EmployeeSchema::make(),
            );
    }
}
