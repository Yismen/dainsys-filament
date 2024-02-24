<?php

namespace App\Filament\HumanResource\Resources\SiteResource\Pages;

use App\Filament\HumanResource\Resources\SiteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSites extends ListRecords
{
    protected static string $resource = SiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
