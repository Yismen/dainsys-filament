<?php

namespace App\Filament\HumanResource\Resources\SuspensionResource\Pages;

use App\Filament\HumanResource\Resources\SuspensionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuspensions extends ListRecords
{
    protected static string $resource = SuspensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
