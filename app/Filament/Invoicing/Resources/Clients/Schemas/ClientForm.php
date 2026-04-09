<?php

namespace App\Filament\Invoicing\Resources\Clients\Schemas;

use App\Services\InvoiceTemplatesService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(500)
                    ->unique(ignoreRecord: true)
                    ->autofocus(),
                Select::make('invoice_template')
                    ->label(__('Invoice template'))
                    ->options(InvoiceTemplatesService::make())
                    ->required()
                    ->searchable(),
                TextInput::make('date_field_name')
                    ->label(__('Date field name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('project_field_name')
                    ->label(__('Project field name'))
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
