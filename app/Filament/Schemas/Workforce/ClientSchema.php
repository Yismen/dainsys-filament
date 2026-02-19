<?php

namespace App\Filament\Schemas\Workforce;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class ClientSchema
{
    public static function make(): array
    {
        return [
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
        ];
    }
}
