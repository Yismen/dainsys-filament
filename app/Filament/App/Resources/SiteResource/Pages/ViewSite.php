<?php

namespace App\Filament\App\Resources\SiteResource\Pages;

use Filament\Actions\EditAction;
use App\Filament\App\Resources\SiteResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSite extends ViewRecord
{
    protected static string $resource = SiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
