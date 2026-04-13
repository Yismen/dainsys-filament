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
                    ->label(__('Invoice')),
                TextEntry::make('amount')
                    ->label(__('Amount'))
                    ->money('USD'),
                TextEntry::make('date')
                    ->label(__('Date'))
                    ->date(),
                TextEntry::make('reference')
                    ->label(__('Reference'))
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->label(__('Description'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->label(__('Deleted at'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label(__('Updated at'))
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
