<?php

namespace App\Filament\Recruitment\Resources\Applicants\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class ApplicantForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('name')
                ->label(__('filament.name'))
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->label(__('filament.email'))
                ->email()
                ->required()
                ->maxLength(255),
            TextInput::make('phone')
                ->label(__('filament.phone'))
                ->nullable()
                ->maxLength(50),
            Textarea::make('notes')
                ->label(__('filament.notes'))
                ->nullable()
                ->columnSpanFull(),
        ];
    }
}
