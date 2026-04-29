<?php

namespace App\Filament\Recruitment\Resources\RecruitmentStages\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class RecruitmentStageForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('name')
                ->label(__('filament.name'))
                ->required()
                ->maxLength(255),
            TextInput::make('order')
                ->label(__('filament.order'))
                ->numeric()
                ->default(0)
                ->required(),
            Textarea::make('description')
                ->label(__('filament.description'))
                ->nullable()
                ->columnSpanFull(),
        ];
    }
}
