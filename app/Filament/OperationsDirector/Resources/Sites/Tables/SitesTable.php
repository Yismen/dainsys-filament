<?php

namespace App\Filament\OperationsDirector\Resources\Sites\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SitesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
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
                TextColumn::make('address')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('geolocation')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
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
