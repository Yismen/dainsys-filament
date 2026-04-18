<?php

namespace App\Filament\ProjectExecutive\Resources\Projects\Tables;

use App\Models\Client;
use App\Services\ModelListService;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.project'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('client.name')
                    ->label(__('filament.client'))
                    ->sortable()
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('employees_count')
                    ->label(__('filament.employees'))
                    ->counts('employees')
                    ->sortable(),
                TextColumn::make('campaigns_count')
                    ->label(__('filament.campaigns'))
                    ->counts('campaigns')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('client_id')
                    ->label(__('filament.client'))
                    ->options(fn (): array => ModelListService::make(Client::query()))
                    ->searchable(),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
