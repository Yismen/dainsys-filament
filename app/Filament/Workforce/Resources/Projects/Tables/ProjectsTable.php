<?php

namespace App\Filament\Workforce\Resources\Projects\Tables;

use App\Models\Client;
use App\Models\User;
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

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('client.name')
                    ->label(__('filament.client'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('manager.name')
                    ->label(__('filament.manager'))
                    ->sortable()
                    ->searchable()
                    ->placeholder(__('filament.unassigned')),
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
                TrashedFilter::make(),
                SelectFilter::make('client_id')
                    ->label(__('filament.client'))
                    ->options(fn () => ModelListService::make(Client::class))
                    ->searchable()
                    ->placeholder(__('filament.select_client')),
                SelectFilter::make('manager_id')
                    ->label(__('filament.manager'))
                    ->options(fn () => ModelListService::make(
                        User::query()->whereHas('roles', function ($query): void {
                            $query->whereIn('name', [
                                'Project Executive Manager',
                                'Project Executive Agent',
                            ]);
                        })
                    ))
                    ->searchable()
                    ->placeholder(__('filament.select_manager')),
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
