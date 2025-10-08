<?php

namespace App\Filament\App\Resources\UniversalResource\Pages;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\CreateAction;
use App\Filament\App\Resources\UniversalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUniversals extends ListRecords implements HasActions
{
    use InteractsWithActions;
    protected static string $resource = UniversalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
