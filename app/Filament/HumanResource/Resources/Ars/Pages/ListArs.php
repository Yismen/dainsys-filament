<?php

namespace App\Filament\HumanResource\Resources\Ars\Pages;

use App\Filament\HumanResource\Resources\Ars\ArsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListArs extends ListRecords
{
    protected static string $resource = ArsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
