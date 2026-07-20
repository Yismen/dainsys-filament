<?php

namespace App\Filament\Invoicing\Resources\Invoices\Schemas;

use App\Models\Invoice;
use App\Models\Item;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class InvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextEntry::make('id')
                    ->label(__('filament.id'))
                    ->columnSpanFull(),
                TextEntry::make('number')
                    ->label(__('filament.number'))
                    ->placeholder('-'),
                TextEntry::make('date')
                    ->label(__('filament.date'))
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('project.name')
                    ->label(__('filament.project'))
                    ->placeholder('-'),
                TextEntry::make('agent.name')
                    ->label(__('filament.agent'))
                    ->placeholder('-'),
                TextEntry::make('campaign.name')
                    ->label(__('filament.campaign'))
                    ->placeholder('-'),
                RepeatableEntry::make('items')
                    ->label(__('filament.items'))
                    ->columns(3)
                    ->schema([
                        TextEntry::make('item_id')
                            ->label(__('filament.item'))
                            ->formatStateUsing(fn (?string $state): string => Item::query()->find($state)?->name ?? '-')
                            ->placeholder('-'),
                        TextEntry::make('quantity')
                            ->label(__('filament.quantity'))
                            ->placeholder('-'),
                        TextEntry::make('price')
                            ->label(__('filament.price'))
                            ->placeholder('-'),
                    ])
                    ->columnSpanFull(),
                TextEntry::make('subtotal_amount')
                    ->label(__('filament.subtotal'))
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tax_amount')
                    ->label(__('filament.tax'))
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('total_amount')
                    ->label(__('filament.total'))
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('total_paid')
                    ->label(__('filament.total_paid'))
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('balance_pending')
                    ->label(__('filament.balance_pending'))
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->label(__('filament.status'))
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('due_date')
                    ->label(__('filament.due_date'))
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->label(__('filament.deleted_at'))
                    ->dateTime()
                    ->visible(fn (Invoice $record): bool => $record->trashed()),
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
