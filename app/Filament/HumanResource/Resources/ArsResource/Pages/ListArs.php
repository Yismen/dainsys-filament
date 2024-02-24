<?php

namespace App\Filament\HumanResource\Resources\ArsResource\Pages;

use App\Filament\HumanResource\Resources\ArsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArs extends ListRecords
{
    protected static string $resource = ArsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
