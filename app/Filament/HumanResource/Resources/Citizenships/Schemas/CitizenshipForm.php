<?php

namespace App\Filament\HumanResource\Resources\Citizenships\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CitizenshipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('filament.name'))
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->autofocus(),
                Textarea::make('description')
                    ->label(__('filament.description'))
                    ->columnSpanFull(),
                RepeatableEntry::make('hiredEmployees')
                    ->label(__('filament.hired_employees'))
                    ->grid(2)
                    ->columnSpanFull()
                    ->visibleOn('edit')
                    ->schema([
                        TextEntry::make('full_name'),
                    ]),
            ]);
    }
}
