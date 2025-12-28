<?php

namespace App\Filament\HumanResource\Resources\Holidays\Pages;

use App\Filament\HumanResource\Resources\Holidays\HolidayResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHoliday extends CreateRecord
{
    protected static string $resource = HolidayResource::class;
}
