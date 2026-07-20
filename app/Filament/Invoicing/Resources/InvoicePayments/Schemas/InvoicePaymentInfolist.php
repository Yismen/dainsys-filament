<?php

namespace App\Filament\Invoicing\Resources\InvoicePayments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class InvoicePaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('invoice.number')
                    ->label(__('filament.invoice')),
                TextEntry::make('amount')
                    ->label(__('filament.amount'))
                    ->money('USD'),
                TextEntry::make('date')
                    ->label(__('filament.date'))
                    ->date(),
                TextEntry::make('reference')
                    ->label(__('filament.reference'))
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->label(__('filament.description'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->label(__('filament.deleted_at'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label(__('filament.updated_at'))
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
