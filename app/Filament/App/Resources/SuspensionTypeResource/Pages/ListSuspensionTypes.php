<?php

namespace App\Filament\App\Resources\SuspensionTypeResource\Pages;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\CreateAction;
use App\Filament\App\Resources\SuspensionTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuspensionTypes extends ListRecords implements HasActions
{
    use InteractsWithActions;
    protected static string $resource = SuspensionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
