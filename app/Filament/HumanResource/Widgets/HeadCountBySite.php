<?php

namespace App\Filament\HumanResource\Widgets;

use App\Models\Site;

class HeadCountBySite extends BaseHumanResourceWidget
{
    protected ?string $heading = 'Head Count by Site';

    protected function getModel(): string
    {
        return Site::class;
    }
}
