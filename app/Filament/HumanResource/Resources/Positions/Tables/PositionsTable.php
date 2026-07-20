<?php

namespace App\Filament\HumanResource\Resources\Positions\Tables;

use App\Enums\EmployeeStatuses;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PositionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.name'))
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('department.name')
                    ->label(__('filament.department'))
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('salary')
                    ->label(__('filament.salary'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('salary_type')
                    ->label(__('filament.salary_type'))
                    ->sortable(),
                TextColumn::make('description')
                    ->label(__('filament.description'))
                    ->limit(50)
                    ->tooltip(fn (string $state) => $state)
                    ->wrap(),
                TextColumn::make('employees_count')
                    ->label(__('filament.hired_employees'))
                    ->counts(['employees' => fn ($query) => $query->where('status', EmployeeStatuses::Hired->value)])
                    ->badge(),
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
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
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
