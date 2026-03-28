<?php

namespace App\Filament\OperationsDirector\Resources\Productions\Pages;

use App\Filament\OperationsDirector\Resources\Productions\ProductionResource;
use Filament\Resources\Pages\ListRecords;

class ListProductions extends ListRecords
{
    protected static string $resource = ProductionResource::class;
}
