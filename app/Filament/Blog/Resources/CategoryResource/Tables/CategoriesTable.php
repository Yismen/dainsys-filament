<?php

namespace App\Filament\Blog\Resources\CategoryResource\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('display_order')
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.name'))
                    ->wrap()
                    ->sortable()
                    ->searchable()
                    ->weight('medium'),
                TextColumn::make('slug')
                    ->label(__('filament.slug'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label(__('filament.description'))
                    ->wrap()
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('accesses.user.name')
                    ->label(__('filament.users'))
                    ->wrap()
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                TextColumn::make('accesses.role.name')
                    ->label(__('filament.roles'))
                    ->wrap()
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                TextColumn::make('display_order')
                    ->label(__('filament.order'))
                    ->sortable(),
                TextColumn::make('articles_count')
                    ->label(__('filament.articles'))
                    ->counts('articles')
                    ->sortable(),
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
                TextColumn::make('deleted_at')
                    ->label(__('filament.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
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
