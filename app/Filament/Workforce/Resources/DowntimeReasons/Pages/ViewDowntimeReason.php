<?php

namespace App\Filament\Workforce\Resources\DowntimeReasons\Pages;

use App\Filament\Workforce\Resources\DowntimeReasons\DowntimeReasonResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDowntimeReason extends ViewRecord
{
    protected static string $resource = DowntimeReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
