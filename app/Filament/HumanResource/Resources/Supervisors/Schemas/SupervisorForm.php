<?php

namespace App\Filament\HumanResource\Resources\Supervisors\Schemas;

use App\Models\User;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SupervisorForm
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
                Select::make('user_id')
                    ->label(__('filament.user'))
                    ->options(ModelListService::make(User::query()))
                    ->searchable()
                    ->required()
                    ->unique(ignoreRecord: true),
                Toggle::make('is_active')
                    ->label(__('filament.is_active'))
                    ->default(true)
                    ->required(),
                Textarea::make('description')
                    ->label(__('filament.description'))
                    ->columnSpanFull(),
            ]);
    }
}
