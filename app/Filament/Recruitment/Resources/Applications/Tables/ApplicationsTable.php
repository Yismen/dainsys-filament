<?php

namespace App\Filament\Recruitment\Resources\Applications\Tables;

use App\Enums\ApplicationStatuses;
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

class ApplicationsTable
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
                TextColumn::make('applicant.name')
                    ->label(__('filament.applicant'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jobOpening.title')
                    ->label(__('filament.job_opening'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('filament.status'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('applied_at')
                    ->label(__('filament.applied_at'))
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
                    ->options(ApplicationStatuses::class)
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
