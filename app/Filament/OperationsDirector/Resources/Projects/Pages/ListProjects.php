<?php

namespace App\Filament\OperationsDirector\Resources\Projects\Pages;

use App\Filament\OperationsDirector\Resources\Projects\ProjectResource;
use Filament\Resources\Pages\ListRecords;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;
}
