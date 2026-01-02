<?php

namespace App\Filament\HumanResource\Resources\Ars\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ArsForm
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
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
