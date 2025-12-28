<?php

namespace App\Filament\HumanResource\Resources\Holidays\Pages;

use App\Filament\HumanResource\Resources\Holidays\HolidayResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewHoliday extends ViewRecord
{
    protected static string $resource = HolidayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
