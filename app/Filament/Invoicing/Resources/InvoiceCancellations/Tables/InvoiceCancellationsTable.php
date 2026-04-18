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
                    ->label(__('filament.invoice'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cancelledBy.name')
                    ->label(__('filament.cancelled_by'))
                    ->sortable()
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('date')
                    ->label(__('filament.date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('reason')
                    ->label(__('filament.reason'))
                    ->searchable()
                    ->limit(60)
                    ->wrap(),
                TextColumn::make('notes')
                    ->label(__('filament.notes'))
                    ->limit(50)
                    ->wrap()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('invoice_id')
                    ->label(__('filament.invoice'))
                    ->options(ModelListService::make(Invoice::query(), 'id', 'number'))
                    ->searchable(),
                TrashedFilter::make()
                    ->label(__('filament.trashed')),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('filament.create_cancellation'))
                    ->modalHeading(__('filament.create_cancellation')),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(__('filament.view'))
                    ->modalHeading(__('filament.view_cancellation')),
                EditAction::make()
                    ->label(__('filament.edit'))
                    ->modalHeading(__('filament.edit_cancellation')),
                DeleteAction::make()
                    ->label(__('filament.delete')),
                RestoreAction::make()
                    ->label(__('filament.restore')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('filament.delete')),
                    ForceDeleteBulkAction::make()
                        ->label(__('filament.force_delete')),
                    RestoreBulkAction::make()
                        ->label(__('filament.restore')),
                ])->label(__('filament.bulk_actions')),
            ]);
    }
}
