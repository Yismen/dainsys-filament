<?php

namespace App\Filament\App\Widgets\HumanResource;

use App\Models\Ars;

class HeadCountByArs extends BaseHumanResourcesWidget
{
    protected ?string $heading = 'Head Count by Ars';

    protected function getModel(): string
    {
        return Ars::class;
    }
}
