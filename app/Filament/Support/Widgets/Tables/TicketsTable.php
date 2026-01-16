<?php

namespace App\Filament\Support\Widgets\Tables;

use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Carbon;

class TicketsTable
{
    public static function make(): array
    {
        return [
            TextColumn::make('reference')
                ->wrap()
                ->searchable(),
            TextColumn::make('owner.name')
                ->wrap()
                ->searchable(),
            TextColumn::make('subject')
                ->wrap()
                ->searchable(),
            ImageColumn::make('images')
                ->disk('public')
                ->circular()
                ->limit(1)
                ->stacked()
                ->limitedRemainingText(),
            TextColumn::make('status')
                ->wrap()
                ->badge()
                ->searchable(),
            TextColumn::make('operator.name')
                ->wrap()
                ->searchable(),
            TextColumn::make('assigned_at')
                ->wrap()
                ->dateTime()
                ->formatStateUsing(fn ($state) => $state->diffForHumans())
                ->sortable(),
            TextColumn::make('priority')
                ->badge()
                ->searchable(),
            TextColumn::make('expected_at')
                ->wrap()
                ->dateTime()
                ->badge()
                ->formatStateUsing(fn ($state) => $state->diffForHumans())
                ->color(fn (Carbon $state) => $state->isPast() ? 'danger' : 'info')
                ->sortable(),
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
        ];
    }
}
