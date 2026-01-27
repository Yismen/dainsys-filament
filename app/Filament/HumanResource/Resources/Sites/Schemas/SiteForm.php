<?php

namespace App\Filament\HumanResource\Resources\Sites\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SiteForm
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
                TextInput::make('person_of_contact')
                    ->nullable(),
                TextInput::make('phone')
                    ->tel()
                    ->nullable(),
                TextInput::make('email')
                    ->nullable()
                    ->email(),
                TextInput::make('geolocation')
                    ->nullable(),
                Textarea::make('address')
                    ->nullable()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->columnSpanFull()
                    ->nullable(),
            ]);
    }
}
