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
                    ->label('Project')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('client.name')
                    ->label('Client')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('employees_count')
                    ->label('Employees')
                    ->counts('employees')
                    ->sortable(),
                TextColumn::make('campaigns_count')
                    ->label('Campaigns')
                    ->counts('campaigns')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('client_id')
                    ->label('Client')
                    ->options(fn (): array => ModelListService::make(Client::query()))
                    ->searchable(),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
