<?php

namespace App\Filament\Schemas\Workforce;

use App\Models\Client;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;

class ProjectSchema
{
    public static function make(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->autofocus(),
            Select::make('client_id')
                ->options(ModelListService::get(Client::class))
                ->searchable()
                ->preload()
                ->relationship('client', 'name')
                ->createOptionModalHeading('Create Client')
                ->createOptionForm([
                    Grid::make(2)
                        ->schema(ClientSchema::make()),
                ])
                ->required(),
            Textarea::make('description')
                ->columnSpanFull(),
        ];
    }
}
