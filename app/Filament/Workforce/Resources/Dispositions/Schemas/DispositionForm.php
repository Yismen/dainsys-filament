<?php

namespace App\Filament\Workforce\Resources\Dispositions\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DispositionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('sales')
                    ->required()
                    ->numeric()
                    ->inputMode('decimal'),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
