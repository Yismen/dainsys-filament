<?php

namespace App\Filament\HumanResource\Resources\SocialSecurities\Pages;

use App\Filament\HumanResource\Resources\SocialSecurities\SocialSecurityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSocialSecurities extends ManageRecords
{
    protected static string $resource = SocialSecurityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
