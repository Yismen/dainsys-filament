<?php

namespace App\Filament\HumanResource\Widgets;

use App\Filament\HumanResource\Widgets\BaseHumanResourceWidget;
use App\Models\Supervisor;

class HeadCountBySupervisor extends BaseHumanResourceWidget
{
    protected ?string $heading = 'Head Count by Supervisor';

    protected function getModel(): string
    {
        return Supervisor::class;
    }
}
