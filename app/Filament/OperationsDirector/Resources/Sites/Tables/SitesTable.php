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
                    ->label(__('filament.name'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('person_of_contact')
                    ->label(__('filament.person_of_contact'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label(__('filament.phone'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('filament.email'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('address')
                    ->label(__('filament.address'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('geolocation')
                    ->label(__('filament.geolocation'))
                    ->wrap()
                    ->sortable()
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
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
