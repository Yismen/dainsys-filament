<?php

namespace App\Filament\OperationsDirector\Resources\Clients\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                TextColumn::make('person_of_contact')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('website')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable()
                    ->wrap()
                    ->limit(50)
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
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
