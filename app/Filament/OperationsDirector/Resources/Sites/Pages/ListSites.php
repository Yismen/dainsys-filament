<?php

namespace App\Filament\OperationsDirector\Resources\Sites\Pages;

use App\Filament\OperationsDirector\Resources\Sites\SiteResource;
use Filament\Resources\Pages\ListRecords;

class ListSites extends ListRecords
{
    protected static string $resource = SiteResource::class;
}
