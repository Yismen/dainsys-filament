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
                    ->label(__('filament.name'))
                    ->required()
                    ->maxLength(500)
                    ->unique(ignoreRecord: true)
                    ->autofocus(),
                Select::make('invoice_template')
                    ->label(__('filament.invoice_template'))
                    ->options(InvoiceTemplatesService::make())
                    ->required()
                    ->searchable(),
                TextInput::make('date_field_name')
                    ->label(__('filament.date_field_name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('project_field_name')
                    ->label(__('filament.project_field_name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('person_of_contact')
                    ->label(__('filament.person_of_contact'))
                    ->maxLength(500),
                TextInput::make('phone')
                    ->label(__('filament.phone'))
                    ->tel(),
                TextInput::make('email')
                    ->label(__('filament.email'))
                    ->email(),
                Textarea::make('address')
                    ->label(__('filament.address'))
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}
