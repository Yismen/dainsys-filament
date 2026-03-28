<?php

namespace App\Schemas\Filament\Workforce;

use App\Models\Client;
use App\Models\User;
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
                ->createOptionModalHeading('Create Client')
                ->createOptionForm([
                    Grid::make(2)
                        ->schema(ClientSchema::make()),
                ])
                ->required(),
            Select::make('manager_id')
                ->label('Manager')
                ->options(ModelListService::get(
                    User::query()->whereHas('roles', function ($query): void {
                        $query->whereIn('name', [
                            'Project Executive Manager',
                            'Project Executive Agent',
                        ]);
                    })
                ))
                ->searchable()
                ->placeholder('Unassigned'),
            Textarea::make('description')
                ->columnSpanFull(),
        ];
    }
}
