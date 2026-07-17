<?php

namespace App\Schemas\Filament\Workforce;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class ClientSchema
{
    public static function make(): array
    {
        return [
            TextInput::make('name')
                ->label(__('filament.name'))
                ->required()
                ->maxLength(500)
                ->unique(ignoreRecord: true)
                ->autofocus(),
            TextInput::make('person_of_contact')
                ->label(__('filament.person_of_contact')),
            TextInput::make('phone')
                ->label(__('filament.phone'))
                ->tel(),
            TextInput::make('email')
                ->label(__('filament.email'))
                ->email(),
            TextInput::make('address')
                ->label(__('filament.address'))
                ->columnSpanFull(),
            TextInput::make('website')
                ->label(__('filament.website'))
                ->url(),
            Textarea::make('description')
                ->label(__('filament.description'))
                ->columnSpanFull(),
        ];
    }
}
