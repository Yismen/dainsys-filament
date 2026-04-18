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
                    ->label(__('filament.employee'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ars.name')
                    ->label(__('filament.ars'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('afp.name')
                    ->label(__('filament.afp'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('number')
                    ->label(__('filament.number'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('deleted_at')
                    ->label(__('filament.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ->filters([
                SelectFilter::make('ars_id')
                    ->label(__('filament.ARS'))
                    ->options(ModelListService::make(Ars::query()))
                    ->searchable(),
                SelectFilter::make('afp_id')
                    ->label(__('filament.AFP'))
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
