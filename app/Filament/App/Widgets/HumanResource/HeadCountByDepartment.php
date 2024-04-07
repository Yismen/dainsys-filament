<?php

namespace App\Filament\App\Widgets\HumanResource;

use App\Models\Department;

class HeadCountByDepartment extends BaseHumanResourcesWidget
{
    protected static ?string $heading = 'Head Count by Department';

    protected function getModel(): string
    {
        return Department::class;
    }
}
