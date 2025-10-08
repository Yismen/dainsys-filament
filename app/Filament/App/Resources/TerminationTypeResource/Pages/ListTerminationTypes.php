<?php

namespace App\Filament\App\Resources\TerminationTypeResource\Pages;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\CreateAction;
use App\Filament\App\Resources\TerminationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTerminationTypes extends ListRecords implements HasActions
{
    use InteractsWithActions;
    protected static string $resource = TerminationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
