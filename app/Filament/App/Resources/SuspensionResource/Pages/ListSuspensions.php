<?php

namespace App\Filament\App\Resources\SuspensionResource\Pages;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\CreateAction;
use App\Filament\App\Resources\SuspensionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuspensions extends ListRecords implements HasActions
{
    use InteractsWithActions;
    protected static string $resource = SuspensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
