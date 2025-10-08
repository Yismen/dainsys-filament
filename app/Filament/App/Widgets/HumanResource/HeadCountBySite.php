<?php

namespace App\Filament\App\Widgets\HumanResource;

use App\Models\Site;

class HeadCountBySite extends BaseHumanResourcesWidget
{
    protected ?string $heading = 'Head Count by Site';

    protected function getModel(): string
    {
        return Site::class;
    }
}
