<?php

namespace App\Filament\Support\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms;

final class PaymentTypeSchema
{
    public static function toArray(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->autofocus()
                ->maxLength(500),
            Textarea::make('description')
                ->maxLength(65535)
                ->columnSpanFull(),
        ];
    }
}
