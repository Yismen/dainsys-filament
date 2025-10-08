<?php

namespace App\Filament\App\Resources\ArsResource\Pages;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\CreateAction;
use App\Filament\App\Resources\ArsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArs extends ListRecords implements HasActions
{
    use InteractsWithActions;
    protected static string $resource = ArsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
