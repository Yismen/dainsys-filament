<?php

namespace App\Filament\App\Resources\PunchResource\Pages;

use App\Filament\App\Resources\PunchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPunches extends ListRecords
{
    protected static string $resource = PunchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
