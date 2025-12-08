<?php

namespace App\Filament\HumanResource\Resources\Sites\Pages;

use App\Filament\HumanResource\Resources\Sites\SiteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSites extends ManageRecords
{
    protected static string $resource = SiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
