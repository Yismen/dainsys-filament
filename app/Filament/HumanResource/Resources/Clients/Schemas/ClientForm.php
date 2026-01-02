<?php

namespace App\Filament\HumanResource\Resources\Clients\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(500)
                    ->unique(ignoreRecord: true)
                    ->autofocus(),
                TextInput::make('person_of_contact'),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->email(),
                TextInput::make('website')
                    ->url(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
