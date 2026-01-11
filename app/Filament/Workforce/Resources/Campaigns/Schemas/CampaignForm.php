<?php

namespace App\Filament\Workforce\Resources\Campaigns\Schemas;

use App\Enums\RevenueTypes;
use App\Models\Project;
use App\Models\Source;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->autofocus()
                    ->unique(ignoreRecord: true)
                    ->maxLength(150)
                    ->required(),
                Select::make('project_id')
                    ->options(ModelListService::make(Project::query()))
                    ->searchable()
                    ->required(),
                Select::make('source_id')
                    ->options(ModelListService::make(Source::query()))
                    ->searchable()
                    ->required(),
                Select::make('revenue_type')
                    ->options(RevenueTypes::class)
                    ->searchable()
                    ->required(),
                TextInput::make('sph_goal')
                    ->required()
                    ->numeric(),
                TextInput::make('revenue_rate')
                    ->required()
                    ->numeric(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
