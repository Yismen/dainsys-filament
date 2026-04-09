<?php

namespace App\Filament\Invoicing\Resources\Agents\Tables;

use App\Models\Project;
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

class AgentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                TextColumn::make('project.name')
                    ->label(__('Project'))
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                TextColumn::make('phone')
                    ->label(__('Phone'))
                    ->sortable()
                    ->searchable()
                    ->wrap()
                    ->placeholder('-'),
                TextColumn::make('email')
                    ->label(__('Email'))
                    ->sortable()
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('deleted_at')
                    ->label(__('Deleted at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()
                    ->label(__('Trashed')),
                SelectFilter::make('project_id')
                    ->label(__('Project'))
                    ->options(ModelListService::get(Project::class))
                    ->searchable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('Create Agent'))
                    ->modalHeading(__('Create Agent')),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(__('View'))
                    ->modalHeading(__('View Agent')),
                EditAction::make()
                    ->label(__('Edit'))
                    ->modalHeading(__('Edit Agent')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('Delete')),
                    ForceDeleteBulkAction::make()
                        ->label(__('Force delete')),
                    RestoreBulkAction::make()
                        ->label(__('Restore')),
                ])->label(__('Bulk actions')),
            ]);
    }
}
