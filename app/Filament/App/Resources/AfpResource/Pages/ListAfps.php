<?php

namespace App\Filament\App\Resources\AfpResource\Pages;

use App\Filament\App\Resources\AfpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAfps extends ListRecords
{
    protected static string $resource = AfpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
