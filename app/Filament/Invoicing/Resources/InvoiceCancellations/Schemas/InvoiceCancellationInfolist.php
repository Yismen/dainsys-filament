<?php

namespace App\Filament\Invoicing\Resources\InvoiceCancellations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class InvoiceCancellationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('invoice.number')
                    ->label(__('Invoice')),
                TextEntry::make('cancelledBy.name')
                    ->label(__('Cancelled by'))
                    ->placeholder('-'),
                TextEntry::make('date')
                    ->label(__('Cancellation date'))
                    ->date(),
                TextEntry::make('reason')
                    ->label(__('Reason'))
                    ->columnSpanFull(),
                TextEntry::make('notes')
                    ->label(__('Notes'))
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
