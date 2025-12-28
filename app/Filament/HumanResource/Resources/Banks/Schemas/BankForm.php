<?php

namespace App\Filament\HumanResource\Resources\Banks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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
                    ->unique(ignoreRecord: true),
                TextInput::make('person_of_contact'),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
