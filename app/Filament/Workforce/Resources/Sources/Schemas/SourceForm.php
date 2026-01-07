<?php

namespace App\Filament\Workforce\Resources\Sources\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SourceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->unique(ignoreRecord: true)
                    ->autofocus()
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
