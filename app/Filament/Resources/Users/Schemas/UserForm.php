<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->unique(ignoreRecord: true)
                            ->email()
                            ->required(),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required(),
                        // DateTimePicker::make('email_verified_at'),
                        // TextInput::make('password')
                        //     ->password()
                        //     ->required(),
                        Toggle::make('is_active')
                            ->visibleOn('edit'),

                        CheckboxList::make('roles')
                            ->relationship('roles', 'name')
                            ->columns(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
