<?php

namespace App\Filament\Recruitment\Resources\Employees\Schemas;

use App\Filament\HumanResource\Resources\Employees\Schemas\EmployeeInfolist as HumanResourceEmployeeInfolist;
use Filament\Schemas\Schema;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return HumanResourceEmployeeInfolist::configure($schema);
    }
}
