<?php

namespace App\Filament\Invoicing\Resources\Campaigns\Schemas;

use App\Enums\RevenueTypes;
use App\Models\InvoiceAgent;
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
                    ->label(__('filament.name'))
                    ->required()
                    ->maxLength(150)
                    ->unique(ignoreRecord: true)
                    ->autofocus(),
                Select::make('project_id')
                    ->label(__('filament.project'))
                    ->options(ModelListService::make(Project::query()))
                    ->searchable()
                    ->required(),
                Select::make('source_id')
                    ->label(__('filament.source'))
                    ->options(ModelListService::make(Source::query()))
                    ->searchable()
                    ->required(),
                Select::make('invoice_agent_id')
                    ->label(__('filament.agent'))
                    ->options(ModelListService::make(InvoiceAgent::query()))
                    ->searchable()
                    ->placeholder(__('filament.unassigned')),
                Select::make('revenue_type')
                    ->label(__('filament.revenue_type'))
                    ->options(RevenueTypes::class)
                    ->searchable()
                    ->required(),
                TextInput::make('sph_goal')
                    ->label(__('filament.sph_goal'))
                    ->required()
                    ->numeric(),
                TextInput::make('revenue_rate')
                    ->label(__('filament.revenue_rate'))
                    ->required()
                    ->numeric(),
                Textarea::make('description')
                    ->label(__('filament.description'))
                    ->columnSpanFull(),
            ]);
    }
}
