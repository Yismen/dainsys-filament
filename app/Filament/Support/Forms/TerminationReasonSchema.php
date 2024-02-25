<?php

namespace App\Filament\Support\Forms;

use Filament\Forms;

final class TerminationReasonSchema
{
    public static function toArray(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->autofocus()
                ->maxLength(500),
            Forms\Components\Textarea::make('description')
                ->maxLength(65535)
                ->columnSpanFull(),
        ];
    }
}
