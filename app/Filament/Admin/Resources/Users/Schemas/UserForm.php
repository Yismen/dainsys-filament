<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use App\Models\Role;
use App\Services\ModelListService;
use Filament\Forms\Components\CheckboxList;
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
                            ->label(__('filament.admin.name'))
                            ->required(),
                        TextInput::make('email')
                            ->label(__('filament.admin.email'))
                            ->unique(ignoreRecord: true)
                            ->email()
                            ->required(),
                        TextInput::make('password')
                            ->label(__('filament.admin.password'))
                            ->password()
                            ->visibleOn('create')
                            ->required(fn (string $context): bool => $context === 'create'),
                        Toggle::make('is_active')
                            ->label(__('filament.admin.is_active'))
                            ->visibleOn('edit'),

                        CheckboxList::make('roles')
                            ->label(__('filament.admin.roles'))
                            ->relationship('roles', 'name')
                            ->options(ModelListService::make(Role::query()))
                            ->columns(2)
                            ->columnSpanFull()
                            ->searchable()
                            ->bulkToggleable(),
                    ]),
            ]);
    }
}
