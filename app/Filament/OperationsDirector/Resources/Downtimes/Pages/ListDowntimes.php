<?php

namespace App\Filament\OperationsDirector\Resources\Downtimes\Pages;

use App\Filament\OperationsDirector\Resources\Downtimes\DowntimeResource;
use Filament\Resources\Pages\ListRecords;

class ListDowntimes extends ListRecords
{
    protected static string $resource = DowntimeResource::class;
}
