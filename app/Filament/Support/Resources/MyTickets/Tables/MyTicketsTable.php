<?php

namespace App\Filament\Support\Resources\MyTickets\Tables;

use App\Enums\TicketStatuses;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class MyTicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            // ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('reference')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->wrap()
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('subject')
                    ->wrap()
                    // ->limit(75)
                    // ->tooltip(fn (string $state) => $state)
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => $state->color() ?? TicketStatuses::from($state)->color())
                    ->sortable()
                    ->searchable(),
                TextColumn::make('agent.name')
                    ->label('Assigned to')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('assigned_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('completed_at')
                    ->wrap()
                    ->dateTime()
                    ->sortable(),
                ImageColumn::make('images')
                    ->disk('public')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText(),
                TextColumn::make('priority')
                    ->badge()
                    ->searchable(),
                TextColumn::make('expected_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
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
