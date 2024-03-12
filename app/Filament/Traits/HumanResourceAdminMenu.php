<?php

namespace App\Filament\Traits;

trait HumanResourceAdminMenu
{

    public static function getNavigationGroup(): ?string
    {
        return 'Human Resources Admin';
    }
}
