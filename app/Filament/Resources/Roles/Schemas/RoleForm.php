<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleForm
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
                        TextInput::make('guard_name')
                            ->required(),
                        CheckboxList::make('users')
                            ->relationship('users', 'name')
                            ->columns(2)
                            ->columnSpanFull(),
                        CheckboxList::make('permissions')
                            ->relationship('permissions', 'name')
                            ->columns(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
