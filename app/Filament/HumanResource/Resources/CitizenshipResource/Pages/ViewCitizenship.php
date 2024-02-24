<?php

namespace App\Filament\HumanResource\Resources\CitizenshipResource\Pages;

use App\Filament\HumanResource\Resources\CitizenshipResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCitizenship extends ViewRecord
{
    protected static string $resource = CitizenshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
