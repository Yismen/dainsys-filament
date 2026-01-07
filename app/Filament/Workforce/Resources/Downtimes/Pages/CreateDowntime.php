<?php

namespace App\Filament\Workforce\Resources\Downtimes\Pages;

use App\Filament\Workforce\Resources\Downtimes\DowntimeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDowntime extends CreateRecord
{
    protected static string $resource = DowntimeResource::class;
}
