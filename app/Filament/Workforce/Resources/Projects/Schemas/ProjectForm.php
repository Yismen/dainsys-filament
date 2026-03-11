<?php

namespace App\Filament\Workforce\Resources\Projects\Schemas;

use App\Schemas\Filament\Workforce\ProjectSchema;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(ProjectSchema::make());
    }
}
