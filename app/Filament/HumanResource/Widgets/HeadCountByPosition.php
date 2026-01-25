<?php

namespace App\Filament\HumanResource\Widgets;

use App\Models\Position;

class HeadCountByPosition extends BaseHumanResourceWidget
{
    protected ?string $heading = 'Head Count by Position';

    protected function getModel(): string
    {
        return Position::class;
    }
}
