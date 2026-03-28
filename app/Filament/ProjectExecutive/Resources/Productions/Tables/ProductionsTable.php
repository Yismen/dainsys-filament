<?php

namespace App\Filament\ProjectExecutive\Resources\Productions\Tables;

use App\Models\Campaign;
use App\Models\Employee;
use App\Models\Production;
use App\Services\ModelListService;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ProductionsTable
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
                    ->label('Employee')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('campaign.name')
                    ->label('Campaign')
                    ->searchable()
                    ->sortable()
                    ->tooltip(fn (Production $record): ?string => $record->campaign?->name)
                    ->wrap(),
                TextColumn::make('conversions')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('production_time')
                    ->label('Production Time')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('revenue')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
            ])
            ->filters([
                Filter::make('date')
                    ->columnSpanFull()
                    ->schema([
                        DatePicker::make('date_from')
                            ->label('Date from'),
                        DatePicker::make('date_until')
                            ->label('Date until'),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'] ?? null,
                                fn (Builder $builder, $date): Builder => $builder->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'] ?? null,
                                fn (Builder $builder, $date): Builder => $builder->whereDate('date', '<=', $date),
                            );
                    }),
                SelectFilter::make('employee_id')
                    ->label('Employee')
                    ->options(function (): array {
                        $managerId = Auth::id();

                        if (! $managerId) {
                            return [];
                        }

                        return ModelListService::make(
                            model: Employee::query()->whereHas('project', function (Builder $query) use ($managerId): void {
                                $query->where('manager_id', $managerId);
                            }),
                            value_field: 'full_name',
                        );
                    })
                    ->searchable(),
                SelectFilter::make('campaign_id')
                    ->label('Campaign')
                    ->options(function (): array {
                        $managerId = Auth::id();

                        if (! $managerId) {
                            return [];
                        }

                        return ModelListService::make(
                            model: Campaign::query()->whereHas('project', function (Builder $query) use ($managerId): void {
                                $query->where('manager_id', $managerId);
                            }),
                        );
                    })
                    ->searchable(),
            ])
            ->filtersFormColumns(2)
            ->filtersFormWidth(Width::Large)
            ->recordActions([
                ViewAction::make(),
            ])
            ->paginated([10, 25, 50, 100]);
    }
}
