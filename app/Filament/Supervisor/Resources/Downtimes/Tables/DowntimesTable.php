<?php

namespace App\Filament\Supervisor\Resources\Downtimes\Tables;

use App\Enums\DowntimeStatuses;
use App\Models\Downtime;
use App\Models\Employee;
use App\Services\ModelListService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DowntimesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('date')
                    ->label(__('filament.date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->label(__('filament.employee'))
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('campaign.name')
                    ->label(__('filament.campaign'))
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('downtimeReason.name')
                    ->label(__('filament.downtime_reason'))
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('total_time')
                    ->label(__('filament.total_time'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('filament.status'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('aprover.name')
                    ->label(__('filament.approver'))
                    ->sortable()
                    ->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                TrashedFilter::make()
                    ->columnSpanFull(),
                Filter::make('date')
                    ->columnSpanFull()
                    ->schema([
                        DatePicker::make('date_from')
                            ->label(__('filament.date_from')),
                        DatePicker::make('date_until')
                            ->label(__('filament.date_until')),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
                SelectFilter::make('status')
                    ->label(__('filament.status'))
                    ->options(DowntimeStatuses::toArray()),

                SelectFilter::make('employee_id')
                    ->label(__('filament.employee'))
                    ->options(ModelListService::make(
                        model: Employee::query()
                            ->active()
                            ->whereHas('supervisor', function (Builder $query): void {
                                $query->where('id', Auth::user()?->supervisor?->id);
                            }),
                        value_field: 'full_name',
                    ))
                    ->searchable(),
            ])
            ->filtersFormColumns(2)
            ->filtersFormWidth(Width::Large)
            ->recordActionsAlignment('left')
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn ($record) => $record->status->value === 'Pending' && $record->employee->supervisor_id === Auth::user()?->supervisor?->id),
                DeleteAction::make()
                    ->visible(fn (Downtime $record): bool => $record->requester_id === auth()->id() && $record->status === DowntimeStatuses::Pending),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
