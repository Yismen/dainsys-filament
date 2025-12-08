<?php

namespace App\Filament\Workforce\Resources\Downtimes\Pages;

use App\Filament\Workforce\Resources\Downtimes\DowntimeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDowntimes extends ManageRecords
{
    protected static string $resource = DowntimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
