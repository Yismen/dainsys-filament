<?php

namespace App\Filament\HumanResource\Resources\Projects\Pages;

use App\Filament\HumanResource\Resources\Projects\ProjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;
}
