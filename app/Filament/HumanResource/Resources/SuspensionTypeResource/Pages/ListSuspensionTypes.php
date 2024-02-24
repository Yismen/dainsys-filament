<?php

namespace App\Filament\HumanResource\Resources\SuspensionTypeResource\Pages;

use App\Filament\HumanResource\Resources\SuspensionTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuspensionTypes extends ListRecords
{
    protected static string $resource = SuspensionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
