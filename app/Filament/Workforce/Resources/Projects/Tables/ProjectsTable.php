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
                    ->sortable()
                    ->searchable(),
                TextColumn::make('client.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('manager.name')
                    ->label('Manager')
                    ->sortable()
                    ->searchable()
                    ->placeholder('Unassigned'),
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
                SelectFilter::make('client_id')
                    ->label('Client')
                    ->options(fn () => ModelListService::make(Client::class))
                    ->searchable()
                    ->placeholder('Select Client'),
                SelectFilter::make('manager_id')
                    ->label('Manager')
                    ->options(fn () => ModelListService::make(
                        User::query()->whereHas('roles', function ($query): void {
                            $query->whereIn('name', [
                                'Project Executive Manager',
                                'Project Executive Agent',
                            ]);
                        })
                    ))
                    ->searchable()
                    ->placeholder('Select Manager'),
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
