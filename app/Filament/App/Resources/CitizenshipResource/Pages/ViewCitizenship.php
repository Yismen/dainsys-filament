<?php

namespace App\Filament\App\Resources\CitizenshipResource\Pages;

use Filament\Actions\EditAction;
use App\Filament\App\Resources\CitizenshipResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCitizenship extends ViewRecord
{
    protected static string $resource = CitizenshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
