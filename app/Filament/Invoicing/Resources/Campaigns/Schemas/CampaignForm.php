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
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(150)
                    ->unique(ignoreRecord: true)
                    ->autofocus(),
                Select::make('project_id')
                    ->label(__('Project'))
                    ->options(ModelListService::make(Project::query()))
                    ->searchable()
                    ->required(),
                Select::make('source_id')
                    ->label(__('Source'))
                    ->options(ModelListService::make(Source::query()))
                    ->searchable()
                    ->required(),
                Select::make('invoice_agent_id')
                    ->label(__('Agent'))
                    ->options(ModelListService::make(InvoiceAgent::query()))
                    ->searchable()
                    ->placeholder(__('Unassigned')),
                Select::make('revenue_type')
                    ->label(__('Revenue type'))
                    ->options(RevenueTypes::class)
                    ->searchable()
                    ->required(),
                TextInput::make('sph_goal')
                    ->label(__('SPH goal'))
                    ->required()
                    ->numeric(),
                TextInput::make('revenue_rate')
                    ->label(__('Revenue rate'))
                    ->required()
                    ->numeric(),
                Textarea::make('description')
                    ->label(__('Description'))
                    ->columnSpanFull(),
            ]);
    }
}
