<?php

namespace App\Filament\Invoicing\Resources\Agents\Schemas;

use App\Models\Project;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AgentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('filament.name'))
                    ->required()
                    ->maxLength(255)
                    ->autofocus(),
                Select::make('project_id')
                    ->label(__('filament.project'))
                    ->options(ModelListService::get(Project::class))
                    ->searchable()
                    ->required(),
                TextInput::make('phone')
                    ->label(__('filament.phone'))
                    ->tel()
                    ->maxLength(20),
                TextInput::make('email')
                    ->label(__('filament.email'))
                    ->email()
                    ->maxLength(200),
            ]);
    }
}
