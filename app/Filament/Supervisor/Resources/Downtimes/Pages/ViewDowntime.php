<?php

namespace App\Filament\Supervisor\Resources\Downtimes\Pages;

use App\Filament\Supervisor\Resources\Downtimes\DowntimeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDowntime extends ViewRecord
{
    protected static string $resource = DowntimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
