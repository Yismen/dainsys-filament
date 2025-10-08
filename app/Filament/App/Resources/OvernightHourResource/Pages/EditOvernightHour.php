<?php

namespace App\Filament\App\Resources\OvernightHourResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\OvernightHourResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOvernightHour extends EditRecord
{
    protected static string $resource = OvernightHourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
