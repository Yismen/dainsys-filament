<?php

namespace App\Filament\HumanResource\Resources\Citizenships\Pages;

use App\Filament\HumanResource\Resources\Citizenships\CitizenshipResource;
use Filament\Actions\EditAction;
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
