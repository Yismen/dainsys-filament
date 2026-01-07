<?php

namespace App\Filament\Workforce\Resources\LoginNames\Pages;

use App\Filament\Workforce\Resources\LoginNames\LoginNameResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLoginNames extends ListRecords
{
    protected static string $resource = LoginNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
