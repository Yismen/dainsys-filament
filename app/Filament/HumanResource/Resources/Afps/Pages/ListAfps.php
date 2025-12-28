<?php

namespace App\Filament\HumanResource\Resources\Afps\Pages;

use App\Filament\HumanResource\Resources\Afps\AfpResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAfps extends ListRecords
{
    protected static string $resource = AfpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
