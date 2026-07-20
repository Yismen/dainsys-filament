<?php

namespace App\Filament\HumanResource\Resources\Ars\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ArsForm
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
                TextInput::make('person_of_contact')
                    ->label(__('filament.person_of_contact')),
                TextInput::make('phone')
                    ->label(__('filament.phone'))
                    ->tel(),
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
