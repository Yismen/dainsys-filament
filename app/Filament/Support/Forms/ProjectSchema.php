<?php

namespace App\Filament\Support\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms;

final class ProjectSchema
{
    public static function toArray(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->autofocus()
                ->unique()
                ->maxLength(500),
            Textarea::make('description')
                ->maxLength(65535)
                ->columnSpanFull(),
        ];
    }
}
