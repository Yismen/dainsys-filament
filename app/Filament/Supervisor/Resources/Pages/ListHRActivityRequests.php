<?php

namespace App\Filament\Supervisor\Resources\Pages;

use App\Filament\Supervisor\Resources\HRActivityRequestResource;
use Filament\Resources\Pages\ListRecords;

class ListHRActivityRequests extends ListRecords
{
    protected static string $resource = HRActivityRequestResource::class;
}
