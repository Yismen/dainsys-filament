<?php

namespace App\Filament\Blog\Resources\ArticleResource\Tables;

use App\Enums\ArticleStatus;
use App\Models\Category;
use App\Models\User;
use App\Services\ModelListService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->weight('medium')
                    ->limit(50),
                TextColumn::make('slug')
                    ->sortable()
                    ->searchable()
                    ->limit(30),
                TextColumn::make('author.name')
                    ->label('Author')
                    ->sortable()
                    ->searchable(),
                BadgeColumn::make('status')
                    ->formatStateUsing(fn (ArticleStatus $state) => $state->name)
                    ->color(fn (ArticleStatus $state) => $state->color())
                    ->sortable(),
                TextColumn::make('categories.name')
                    ->label('Categories')
                    ->badge()
                    ->separator(',')
                    ->wrap(),
                // TextColumn::make('published_at')
                //     ->label('Published')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->options(ArticleStatus::class),
                SelectFilter::make('categories')
                    ->relationship('categories', 'name')
                    ->options(ModelListService::make(Category::query()))
                    ->searchable(),
                SelectFilter::make('author_id')
                    ->label('Author')
                    ->relationship('author', 'name')
                    ->options(ModelListService::make(User::query()))
                    ->searchable(),
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
