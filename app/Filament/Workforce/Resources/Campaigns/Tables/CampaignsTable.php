<?php

namespace App\Filament\Workforce\Resources\Campaigns\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CampaignsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('project.name')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('source.name')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('revenue_type')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('sph_goal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('revenue_rate')
                    ->numeric()
                    ->sortable()
                    ->sortable(),
                TextColumn::make('description')
                    ->limit(30)
                    ->tooltip(fn (string $state) => $state)
                    ->wrap()
                    ->searchable(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
