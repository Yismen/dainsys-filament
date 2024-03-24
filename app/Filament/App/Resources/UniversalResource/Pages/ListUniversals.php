<?php

namespace App\Filament\App\Resources\UniversalResource\Pages;

use App\Filament\App\Resources\UniversalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUniversals extends ListRecords
{
    protected static string $resource = UniversalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
