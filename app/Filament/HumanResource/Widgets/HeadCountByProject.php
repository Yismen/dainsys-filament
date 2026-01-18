<?php

namespace App\Filament\HumanResource\Widgets;

use App\Filament\HumanResource\Widgets\BaseHumanResourceWidget;
use App\Models\Project;

class HeadCountByProject extends BaseHumanResourceWidget
{
    protected ?string $heading = 'Head Count by Project';

    protected function getModel(): string
    {
        return Project::class;
    }
}
