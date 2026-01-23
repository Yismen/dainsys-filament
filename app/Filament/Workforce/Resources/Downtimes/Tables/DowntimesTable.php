<?php

namespace App\Filament\Workforce\Resources\Downtimes\Tables;

use App\Filament\Actions\AproveDowntimeAction;
use App\Models\Downtime;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class DowntimesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('campaign.name')
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('downtimeReason.name')
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('total_time')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('requester.name')
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('status')
                    ->color(fn ($state) => $state == 'Aproved' ? Color::Green : Color::Gray)
                    ->badge(),
                TextColumn::make('aprover.name')
                    ->sortable()
                    ->wrap()
                    ->searchable(),
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
                    AproveDowntimeAction::make('aprove downtimes')
                        ->visible(true)
                        ->accessSelectedRecords()
                        ->action(function (Collection $records) {
                            $records->each(function (Downtime $record) {
                                if ($record->aprover_id === null) {
                                    $record->aprove();
                                }
                            });
                        }),
                ]),
            ]);
    }
}
