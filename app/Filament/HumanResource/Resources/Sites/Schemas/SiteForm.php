<?php

namespace App\Filament\HumanResource\Resources\Sites\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SiteForm
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
                    ->label(__('filament.person_of_contact'))
                    ->nullable(),
                TextInput::make('phone')
                    ->label(__('filament.phone'))
                    ->tel()
                    ->nullable(),
                TextInput::make('email')
                    ->label(__('filament.email'))
                    ->nullable()
                    ->email(),
                TextInput::make('geolocation')
                    ->label(__('filament.geolocation'))
                    ->nullable(),
                Textarea::make('address')
                    ->label(__('filament.address'))
                    ->nullable()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label(__('filament.description'))
                    ->columnSpanFull()
                    ->nullable(),
                RepeatableEntry::make('hiredEmployees')
                    ->label(__('Hired Employees'))
                    ->grid(2)
                    ->columnSpanFull()
                    ->visibleOn('edit')
                    ->schema([
                        TextEntry::make('full_name'),
                    ]),
            ]);
    }
}
