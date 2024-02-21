<?php

namespace App\Filament\App\Resources;

use Filament\Resources\Resource;

abstract class HumanResourceResource extends Resource
{
    public static function getNavigationGroup(): ?string
    {
        return trans('Human Resources');
    }
}
