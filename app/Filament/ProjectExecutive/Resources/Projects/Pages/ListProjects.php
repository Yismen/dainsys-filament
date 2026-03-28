<?php

namespace App\Filament\ProjectExecutive\Resources\Projects\Pages;

use App\Filament\ProjectExecutive\Resources\Projects\ProjectResource;
use Filament\Resources\Pages\ListRecords;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;
}
