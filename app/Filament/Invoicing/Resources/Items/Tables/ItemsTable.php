<?php

namespace App\Filament\Invoicing\Resources\Items\Tables;

use App\Models\Campaign;
use App\Services\ModelListService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.name'))
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('campaign.name')
                    ->label(__('filament.campaign'))
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('price')
                    ->label(__('filament.price'))
                    ->numeric()
                    ->sortable(),
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
                SelectFilter::make('campaign_id')
                    ->label(__('filament.campaign'))
                    ->options(ModelListService::make(Campaign::query()))
                    ->searchable(),
                TrashedFilter::make()
                    ->label(__('filament.trashed')),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('filament.create_item'))
                    ->modalHeading(__('filament.create_item')),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(__('filament.view'))
                    ->modalHeading(__('filament.view_item')),
                EditAction::make()
                    ->label(__('filament.edit'))
                    ->modalHeading(__('filament.edit_item')),
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
