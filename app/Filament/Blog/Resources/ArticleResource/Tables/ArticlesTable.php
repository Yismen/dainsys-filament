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
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\IconColumn;
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
                    ->label(__('filament.title'))
                    ->wrap()
                    ->sortable()
                    ->searchable()
                    ->weight('medium'),
                TextColumn::make('slug')
                    ->label(__('filament.slug'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('author.name')
                    ->label(__('filament.author'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('filament.status'))
                    ->formatStateUsing(fn (ArticleStatus $state) => $state->name)
                    ->color(fn (ArticleStatus $state) => $state->color())
                    ->badge()
                    ->sortable(),
                IconColumn::make('is_public')
                    ->label(__('filament.public'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('categories.name')
                    ->label(__('filament.categories'))
                    ->wrap()
                    ->badge()
                    ->separator(',')
                    ->wrap(),
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
                SelectFilter::make('status')
                    ->label(__('filament.status'))
                    ->options(ArticleStatus::class),
                SelectFilter::make('is_public')
                    ->label(__('filament.public'))
                    ->options([
                        true => 'Yes',
                        false => 'No',
                    ]),
                SelectFilter::make('categories')
                    ->label(__('filament.categories'))
                    ->relationship('categories', 'name')
                    ->options(ModelListService::make(Category::query()))
                    ->searchable(),
                SelectFilter::make('author_id')
                    ->label(__('filament.author'))
                    ->relationship('author', 'name')
                    ->options(ModelListService::make(User::query()))
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalWidth(Width::FiveExtraLarge)
                    ->closeModalByClickingAway(false),
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
