<?php

namespace App\Filament\ProjectExecutive\Resources\Downtimes\Pages;

use App\Filament\ProjectExecutive\Resources\Downtimes\DowntimeResource;
use Filament\Resources\Pages\ListRecords;

class ListDowntimes extends ListRecords
{
    protected static string $resource = DowntimeResource::class;
}
