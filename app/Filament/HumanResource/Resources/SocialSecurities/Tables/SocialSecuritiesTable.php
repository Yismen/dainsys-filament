<?php

namespace App\Filament\HumanResource\Resources\SocialSecurities\Tables;

use App\Models\Afp;
use App\Models\Ars;
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

class SocialSecuritiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('employee.full_name')
            ->columns([
                TextColumn::make('employee.full_name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ars.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('afp.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('number')
                    ->sortable()
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
                SelectFilter::make('ars_id')
                    ->label(__('ARS'))
                    ->options(ModelListService::make(Ars::query()))
                    ->searchable(),
                SelectFilter::make('afp_id')
                    ->label(__('AFP'))
                    ->options(ModelListService::make(Afp::query()))
                    ->searchable(),
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
