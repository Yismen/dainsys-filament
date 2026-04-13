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
            ->components([
                TextEntry::make('id')
                    ->label(__('ID'))
                    ->columnSpanFull(),
                TextEntry::make('number')
                    ->label(__('Number'))
                    ->placeholder('-'),
                TextEntry::make('date')
                    ->label(__('Date'))
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('project.name')
                    ->label(__('Project'))
                    ->placeholder('-'),
                TextEntry::make('agent.name')
                    ->label(__('Agent'))
                    ->placeholder('-'),
                TextEntry::make('campaign.name')
                    ->label(__('Campaign'))
                    ->placeholder('-'),
                RepeatableEntry::make('items')
                    ->label(__('Items'))
                    ->columns(3)
                    ->schema([
                        TextEntry::make('item_id')
                            ->label(__('Item'))
                            ->formatStateUsing(fn (?string $state): string => Item::query()->find($state)?->name ?? '-')
                            ->placeholder('-'),
                        TextEntry::make('quantity')
                            ->label(__('Quantity'))
                            ->placeholder('-'),
                        TextEntry::make('price')
                            ->label(__('Price'))
                            ->placeholder('-'),
                    ])
                    ->columnSpanFull(),
                TextEntry::make('subtotal_amount')
                    ->label(__('Subtotal'))
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tax_amount')
                    ->label(__('Tax'))
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('total_amount')
                    ->label(__('Total'))
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('total_paid')
                    ->label(__('Total paid'))
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('balance_pending')
                    ->label(__('Balance pending'))
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('due_date')
                    ->label(__('Due date'))
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->label(__('Deleted at'))
                    ->dateTime()
                    ->visible(fn (Invoice $record): bool => $record->trashed()),
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
