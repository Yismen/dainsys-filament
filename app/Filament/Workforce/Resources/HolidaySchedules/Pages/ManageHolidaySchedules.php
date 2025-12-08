<?php

namespace App\Filament\Workforce\Resources\HolidaySchedules\Pages;

use App\Filament\Workforce\Resources\HolidaySchedules\HolidayScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageHolidaySchedules extends ManageRecords
{
    protected static string $resource = HolidayScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
