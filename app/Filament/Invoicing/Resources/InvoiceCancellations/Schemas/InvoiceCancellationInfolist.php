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
                    ->label(__('filament.invoice')),
                TextEntry::make('cancelledBy.name')
                    ->label(__('filament.cancelled_by'))
                    ->placeholder('-'),
                TextEntry::make('date')
                    ->label(__('filament.cancellation_date'))
                    ->date(),
                TextEntry::make('reason')
                    ->label(__('filament.reason'))
                    ->columnSpanFull(),
                TextEntry::make('notes')
                    ->label(__('filament.notes'))
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
