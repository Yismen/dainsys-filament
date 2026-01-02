<?php

namespace App\Filament\HumanResource\Resources\Holidays\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class HolidayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->autofocus(),
                DatePicker::make('date')
                    ->required()
                    ->unique(ignoreRecord: true),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
