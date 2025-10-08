<?php

namespace App\Filament\App\Widgets\HumanResource;

use App\Models\Afp;

class HeadCountByAfp extends BaseHumanResourcesWidget
{
    protected ?string $heading = 'Head Count by Afp';

    protected function getModel(): string
    {
        return Afp::class;
    }
}
