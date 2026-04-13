<?php

namespace App\Filament\Invoicing\Resources\InvoiceCancellations\Tables;

use App\Models\Invoice;
use App\Services\ModelListService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class InvoiceCancellationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('invoice.number')
                    ->label(__('Invoice'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cancelledBy.name')
                    ->label(__('Cancelled by'))
                    ->sortable()
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('date')
                    ->label(__('Date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('reason')
                    ->label(__('Reason'))
                    ->searchable()
                    ->limit(60)
                    ->wrap(),
                TextColumn::make('notes')
                    ->label(__('Notes'))
                    ->limit(50)
                    ->wrap()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('invoice_id')
                    ->label(__('Invoice'))
                    ->options(ModelListService::make(Invoice::query(), 'id', 'number'))
                    ->searchable(),
                TrashedFilter::make()
                    ->label(__('Trashed')),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('Create Cancellation'))
                    ->modalHeading(__('Create Cancellation')),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(__('View'))
                    ->modalHeading(__('View Cancellation')),
                EditAction::make()
                    ->label(__('Edit'))
                    ->modalHeading(__('Edit Cancellation')),
                DeleteAction::make()
                    ->label(__('Delete')),
                RestoreAction::make()
                    ->label(__('Restore')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('Delete')),
                    ForceDeleteBulkAction::make()
                        ->label(__('Force delete')),
                    RestoreBulkAction::make()
                        ->label(__('Restore')),
                ])->label(__('Bulk actions')),
            ]);
    }
}
