<?php

namespace App\Filament\HumanResource\Resources\Projects\Schemas;

use App\Models\Client;
use Filament\Schemas\Schema;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->autofocus(),
                Select::make('client_id')
                    ->relationship('client', 'name')
                    ->options(ModelListService::get(Client::class))
                    ->searchable()
                    ->preload()
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
