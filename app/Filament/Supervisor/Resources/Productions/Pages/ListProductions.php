<?php

namespace App\Filament\Supervisor\Resources\Productions\Pages;

use App\Filament\Supervisor\Resources\Productions\ProductionResource;
use Filament\Resources\Pages\ListRecords;

class ListProductions extends ListRecords
{
    protected static string $resource = ProductionResource::class;
}
