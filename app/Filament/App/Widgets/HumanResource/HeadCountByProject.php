<?php

namespace App\Filament\App\Widgets\HumanResource;

use App\Models\Project;

class HeadCountByProject extends BaseHumanResourcesWidget
{
    protected static ?string $heading = 'Head Count by Project';

    protected function getModel(): string
    {
        return Project::class;
    }
}
