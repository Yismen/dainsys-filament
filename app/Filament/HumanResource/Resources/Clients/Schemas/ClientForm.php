<?php

namespace App\Filament\HumanResource\Resources\Clients\Schemas;

use App\Filament\Schemas\Workforce\ClientSchema;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(ClientSchema::make());
    }
}
