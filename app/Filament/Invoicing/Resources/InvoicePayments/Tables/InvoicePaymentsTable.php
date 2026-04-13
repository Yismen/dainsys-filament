<?php

namespace App\Filament\Invoicing\Resources\InvoicePayments\Tables;

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

class InvoicePaymentsTable
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
                TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('date')
                    ->label(__('Date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('reference')
                    ->label(__('Reference'))
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('description')
                    ->label(__('Description'))
                    ->limit(50)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('Deleted at'))
                    ->dateTime()
                    ->sortable()
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
                    ->label(__('Create Payment'))
                    ->modalHeading(__('Create Payment')),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(__('View'))
                    ->modalHeading(__('View Payment')),
                EditAction::make()
                    ->label(__('Edit'))
                    ->modalHeading(__('Edit Payment')),
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
