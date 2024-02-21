<?php

namespace App\Filament\App\Resources\TerminationTypeResource\Pages;

use App\Filament\App\Resources\TerminationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTerminationTypes extends ListRecords
{
    protected static string $resource = TerminationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
