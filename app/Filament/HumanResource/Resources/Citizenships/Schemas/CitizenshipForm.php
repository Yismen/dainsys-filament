<?php

namespace App\Filament\HumanResource\Resources\Citizenships\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CitizenshipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->autofocus(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
