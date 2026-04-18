<?php

namespace App\Filament\QA\Resources\QAForms\Tables;

use App\Models\Project;
use App\Services\ModelListService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class QAFormsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('project.name')
                    ->label(__('filament.project'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('passing_threshold_percentage')
                    ->label(__('filament.threshold'))
                    ->sortable()
                    ->badge(),
                TextColumn::make('author.name')
                    ->label(__('filament.author'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('questions_count')
                    ->counts('questions')
                    ->label(__('filament.questions'))
                    ->sortable(),
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
            ])
            ->filters([
                SelectFilter::make('project_id')
                    ->label(__('filament.project'))
                    ->options(ModelListService::make(Project::query()))
                    ->searchable(),
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
