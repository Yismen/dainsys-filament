<?php

namespace App\Filament\Workforce\Resources\Downtimes\Pages;

use App\Filament\Workforce\Resources\Downtimes\DowntimeResource;
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
