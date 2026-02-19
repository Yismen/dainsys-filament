<?php

namespace App\Filament\Schemas\Workforce;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class SourceSchema
{
    public static function make(): array
    {
        return [
            TextInput::make('name')
                ->unique(ignoreRecord: true)
                ->autofocus()
                ->required(),
            Textarea::make('description')
                ->columnSpanFull(),
        ];
    }
}
