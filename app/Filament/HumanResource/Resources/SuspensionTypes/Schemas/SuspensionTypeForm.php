<?php

namespace App\Filament\HumanResource\Resources\SuspensionTypes\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SuspensionTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(250)
                    ->unique(ignoreRecord: true)
                    ->autofocus(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
