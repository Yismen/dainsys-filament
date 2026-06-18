<?php

namespace App\Filament\HumanResource\Resources\Banks\Tables;

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

class BanksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('person_of_contact')
                    ->label(__('filament.person_of_contact'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('employees_count')
                    ->label(__('Hired Employees'))
                    ->counts(['employees' => fn ($query) => $query->where('status', EmployeeStatuses::Hired->value)])
                    ->badge(),
                TextColumn::make('phone')
                    ->label(__('filament.phone'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make(name: 'email')
                    ->label(__('filament.email'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label(__('filament.description'))
                    ->searchable()
                    ->limit(50)
                    ->wrap()
                    ->tooltip(fn (string $state) => $state),
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
