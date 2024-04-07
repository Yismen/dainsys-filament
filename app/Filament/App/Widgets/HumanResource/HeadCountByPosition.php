<?php

namespace App\Filament\App\Widgets\HumanResource;

use App\Models\Position;

class HeadCountByPosition extends BaseHumanResourcesWidget
{
    protected static ?string $heading = 'Head Count by Position';

    protected function getModel(): string
    {
        return Position::class;
    }
}
