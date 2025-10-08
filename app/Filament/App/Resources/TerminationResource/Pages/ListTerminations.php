<?php

namespace App\Filament\App\Resources\TerminationResource\Pages;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\CreateAction;
use App\Filament\App\Resources\TerminationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTerminations extends ListRecords implements HasActions
{
    use InteractsWithActions;
    protected static string $resource = TerminationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
