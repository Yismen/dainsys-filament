<?php

namespace App\Filament\Supervisor\Resources\NightlyHours\Pages;

use App\Filament\Supervisor\Resources\NightlyHours\NightlyHourResource;
use Filament\Resources\Pages\ListRecords;

class ListNightlyHours extends ListRecords
{
    protected static string $resource = NightlyHourResource::class;
}
