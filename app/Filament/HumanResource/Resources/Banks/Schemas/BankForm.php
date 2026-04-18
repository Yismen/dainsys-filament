<?php

namespace App\Filament\HumanResource\Resources\Banks\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BankForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('filament.name'))
                    ->required()
                    ->maxLength(255)
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
                Textarea::make('description')
                    ->label(__('filament.description'))
                    ->columnSpanFull(),
            ]);
    }
}
