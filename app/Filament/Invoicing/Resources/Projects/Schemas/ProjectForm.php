<?php

namespace App\Filament\Invoicing\Resources\Projects\Schemas;

use App\Models\Client;
use App\Models\User;
use App\Services\ModelListService;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(500)
                    ->unique(ignoreRecord: true)
                    ->autofocus(),
                Select::make('client_id')
                    ->label(__('Client'))
                    ->options(ModelListService::make(Client::class))
                    ->searchable()
                    ->required(),
                TextInput::make('invoice_net_days')
                    ->label(__('Invoice net days'))
                    ->numeric()
                    ->minValue(1)
                    ->required(),
                RichEditor::make('address')
                    ->label(__('Address'))
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->required(),
                RichEditor::make('invoice_notes')
                    ->label(__('Invoice Notes'))
                    ->columnSpanFull()
                    ->required(),
                Textarea::make('description')
                    ->label(__('Description'))
                    ->columnSpanFull(),
            ]);
    }
}
