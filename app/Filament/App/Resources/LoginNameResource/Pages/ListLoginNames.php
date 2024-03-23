<?php

namespace App\Filament\App\Resources\LoginNameResource\Pages;

use App\Filament\App\Resources\LoginNameResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoginNames extends ListRecords
{
    protected static string $resource = LoginNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
