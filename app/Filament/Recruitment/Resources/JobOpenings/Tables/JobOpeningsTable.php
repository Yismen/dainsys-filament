<?php

namespace App\Filament\Recruitment\Resources\JobOpenings\Tables;

use App\Enums\JobOpeningStatuses;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class JobOpeningsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament.id'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->label(__('filament.title'))
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('filament.status'))
                    ->wrap()
                    ->badge()
                    ->sortable(),
                TextColumn::make('position.name')
                    ->label(__('filament.position'))
                    ->wrap()
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('department.name')
                    ->label(__('filament.department'))
                    ->wrap()
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('site.name')
                    ->label(__('filament.site'))
                    ->wrap()
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('openings_count')
                    ->label(__('filament.openings_count'))
                    ->wrapHeader()
                    ->sortable(),
                TextColumn::make('opened_at')
                    ->label(__('filament.opened_at'))
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('filament.created_at'))
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
                    ->options(JobOpeningStatuses::class)
                    ->searchable(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(__('filament.view'))
                    ->stickyModalHeader()
                    ->stickyModalFooter()
                    ->closeModalByClickingAway(false),
                EditAction::make()
                    ->label(__('filament.edit'))
                    ->stickyModalHeader()
                    ->stickyModalFooter()
                    ->closeModalByClickingAway(false),
                DeleteAction::make()
                    ->label(__('filament.delete')),
                RestoreAction::make()
                    ->label(__('filament.restore')),
                ForceDeleteAction::make()
                    ->label(__('filament.force_delete')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
