<?php

namespace App\Filament\App\Resources\TerminationResource\Pages;

use App\Filament\App\Resources\TerminationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTerminations extends ListRecords
{
    protected static string $resource = TerminationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
