<?php

namespace App\Filament\Support\Forms;

use Filament\Forms;

final class ProjectSchema
{
    public static function toArray(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->autofocus()
                ->unique()
                ->maxLength(500),
            Forms\Components\Textarea::make('description')
                ->maxLength(65535)
                ->columnSpanFull(),
        ];
    }
}
