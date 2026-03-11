<?php

namespace App\Filament\Workforce\Resources\Clients\Schemas;

use App\Schemas\Filament\Workforce\ClientSchema;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(ClientSchema::make());
    }
}
