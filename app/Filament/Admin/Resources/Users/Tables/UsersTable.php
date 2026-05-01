<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use App\Actions\Filament\Admin\ResetUserPasswordAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class UsersTable
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
                TextColumn::make('email')
                    ->label(__('filament.email'))
                    ->copyable()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label(__('filament.roles'))
                    ->searchable()
                    ->badge()
                    ->wrap(),
                ToggleColumn::make('is_active')
                    ->label(__('filament.is_active'))
                    ->sortable(),
                IconColumn::make('employee_id')
                    ->label(__('filament.employee'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('email_verified_at')
                    ->label(__('filament.email_verified'))
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
                TextColumn::make('deleted_at')
                    ->label(__('filament.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                TernaryFilter::make('is_active')
                    ->default(true)
                    ->label(__('filament.is_active'))
                    ->trueLabel(__('filament.active'))
                    ->falseLabel(__('filament.inactive')),
                TernaryFilter::make('employee_id')
                    ->label(__('filament.employee'))
                    ->default(false)
                    ->trueLabel(__('filament.has_employee_id'))
                    ->falseLabel(__('filament.no_employee_id'))
                    ->query(function ($query, $data): void {
                        $value = $data['value'] ?? null;

                        if ($value === null) {
                            return;
                        }

                        $value = (bool) $value;

                        if ($value === true) {
                            $query->whereNotNull('employee_id');
                        } elseif ($value === false) {
                            $query->whereNull('employee_id');
                        }
                    }),
                TernaryFilter::make('email_verified_at')
                    ->label(__('filament.email_verified'))
                    ->trueLabel(__('filament.verified'))
                    ->falseLabel(__('filament.not_verified')),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                ResetUserPasswordAction::make()
                    ->iconButton(),
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
