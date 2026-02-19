<?php

namespace App\Filament\Workforce\Resources\Sources\Schemas;

use App\Filament\Schemas\Workforce\SourceSchema;
use Filament\Schemas\Schema;

class SourceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(SourceSchema::make());
    }
}
