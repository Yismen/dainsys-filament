<?php

namespace App\Filament\HumanResource\Resources\Projects\Schemas;

use App\Filament\Schemas\Workforce\ProjectSchema;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(ProjectSchema::make());
    }
}
