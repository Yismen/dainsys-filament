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
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->autofocus(),
                TextInput::make('person_of_contact'),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->email(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
