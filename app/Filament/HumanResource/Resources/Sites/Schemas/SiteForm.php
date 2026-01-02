<?php

namespace App\Filament\HumanResource\Resources\Sites\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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
                TextInput::make('person_of_contact'),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->email(),
                TextInput::make('geolocation'),
                Textarea::make('address')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
