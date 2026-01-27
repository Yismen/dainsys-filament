<?php

namespace App\Filament\Supervisor\Resources\Downtimes\Pages;

use App\Filament\Supervisor\Resources\Downtimes\DowntimeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDowntimes extends ListRecords
{
    protected static string $resource = DowntimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
