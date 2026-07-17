<?php

namespace App\Filament\QA\Resources\QAForms\Schemas;

use App\Models\Project;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QAFormForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(150)
                    ->unique(ignoreRecord: true)
                    ->autofocus(),
                Select::make('project_id')
                    ->label(__('filament.project'))
                    ->options(ModelListService::make(Project::query()))
                    ->searchable()
                    ->required(),
                TextInput::make('passing_threshold_percentage')
                    ->label(__('filament.passing_threshold_percentage'))
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
