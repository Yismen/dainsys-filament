<?php

namespace App\Filament\Invoicing\Resources\InvoicePayments\Schemas;

use App\Models\Invoice;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvoicePaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('invoice_id')
                    ->label(__('filament.invoice'))
                    ->options(ModelListService::make(Invoice::query(), 'id', 'number'))
                    ->required()
                    ->searchable(),
                TextInput::make('amount')
                    ->label(__('filament.amount'))
                    ->required()
                    ->numeric()
                    ->minValue(0.01)
                    ->prefix('$'),
                DatePicker::make('date')
                    ->label(__('filament.date'))
                    ->required(),
                TextInput::make('reference')
                    ->label(__('filament.reference'))
                    ->maxLength(255),
                Textarea::make('description')
                    ->label(__('filament.description'))
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
