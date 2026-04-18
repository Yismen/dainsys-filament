<?php

namespace App\Filament\HumanResource\Resources\Absences\Tables;

use App\Enums\AbsenceStatuses;
use App\Enums\AbsenceTypes;
use App\Filament\HumanResource\Resources\Absences\AbsenceResource;
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

class AbsencesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('employee.full_name')
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament.id'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('employee.full_name')
                    ->label(__('filament.employee'))
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label(__('filament.date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('filament.status'))
                    ->badge()
                    ->searchable(),
                TextColumn::make('type')
                    ->label(__('filament.type'))
                    ->badge()
                    ->placeholder('Pending')
                    ->searchable(),
                TextColumn::make('creator.name')
                    ->label(__('filament.reported_by'))
                    ->searchable(),
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
                    ->options(AbsenceStatuses::class)
                    ->searchable(),
                SelectFilter::make('type')
                    ->options(AbsenceTypes::class)
                    ->searchable(),
            ])
            ->recordActions([
                ViewAction::make(),
                AbsenceResource::markAsReportedAction(),
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
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
