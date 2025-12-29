<?php

namespace App\Filament\HumanResource\Resources\SuspensionTypes\Pages;

use App\Filament\HumanResource\Resources\SuspensionTypes\SuspensionTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSuspensionTypes extends ListRecords
{
    protected static string $resource = SuspensionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
