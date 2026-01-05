<?php

namespace App\Filament\HumanResource\Resources\SocialSecurities\Pages;

use App\Filament\HumanResource\Resources\SocialSecurities\SocialSecurityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSocialSecurities extends ListRecords
{
    protected static string $resource = SocialSecurityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
