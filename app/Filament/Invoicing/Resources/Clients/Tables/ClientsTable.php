<?php

namespace App\Filament\Invoicing\Resources\Clients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.name'))
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                TextColumn::make('invoice_template')
                    ->label(__('filament.invoice_template'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('date_field_name')
                    ->label(__('filament.date_field_name'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('project_field_name')
                    ->label(__('filament.project_field_name'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label(__('filament.description'))
                    ->searchable()
                    ->wrap()
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('filament.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('filament.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()
                    ->label(__('filament.trashed')),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('filament.create_client'))
                    ->modalHeading(__('filament.create_client')),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(__('filament.view'))
                    ->modalHeading(__('filament.view_client')),
                EditAction::make()
                    ->label(__('filament.edit'))
                    ->modalHeading(__('filament.edit_client')),
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
