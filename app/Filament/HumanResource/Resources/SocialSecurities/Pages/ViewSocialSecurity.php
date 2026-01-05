<?php

namespace App\Filament\HumanResource\Resources\SocialSecurities\Pages;

use App\Filament\HumanResource\Resources\SocialSecurities\SocialSecurityResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSocialSecurity extends ViewRecord
{
    protected static string $resource = SocialSecurityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
