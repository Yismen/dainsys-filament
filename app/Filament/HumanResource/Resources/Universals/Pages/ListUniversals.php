<?php

namespace App\Filament\HumanResource\Resources\Universals\Pages;

use App\Filament\HumanResource\Resources\Universals\UniversalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUniversals extends ListRecords
{
    protected static string $resource = UniversalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
