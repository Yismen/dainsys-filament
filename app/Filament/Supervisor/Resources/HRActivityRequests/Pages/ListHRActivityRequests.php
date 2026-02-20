<?php

namespace App\Filament\Supervisor\Resources\HRActivityRequests\Pages;

use App\Filament\Supervisor\Resources\HRActivityRequests\HRActivityRequestResource;
use Filament\Resources\Pages\ListRecords;

class ListHRActivityRequests extends ListRecords
{
    protected static string $resource = HRActivityRequestResource::class;
}
