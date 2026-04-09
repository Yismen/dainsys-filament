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
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255)
                    ->autofocus(),
                Select::make('project_id')
                    ->label(__('Project'))
                    ->options(ModelListService::get(Project::class))
                    ->searchable()
                    ->required(),
                TextInput::make('phone')
                    ->label(__('Phone'))
                    ->tel()
                    ->maxLength(20),
                TextInput::make('email')
                    ->label(__('Email'))
                    ->email()
                    ->maxLength(200),
            ]);
    }
}
