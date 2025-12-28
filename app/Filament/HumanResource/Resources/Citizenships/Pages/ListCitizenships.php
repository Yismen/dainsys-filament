<?php

namespace App\Filament\HumanResource\Resources\Citizenships\Pages;

use App\Filament\HumanResource\Resources\Citizenships\CitizenshipResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCitizenships extends ListRecords
{
    protected static string $resource = CitizenshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
