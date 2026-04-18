<?php

namespace App\Filament\OperationsDirector\Resources\Productions\Tables;

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

class ProductionsTable
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
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('campaign.name')
                    ->label(__('filament.campaign'))
                    ->searchable()
                    ->sortable()
                    ->tooltip(fn (Production $record): ?string => $record->campaign?->name)
                    ->wrap(),
                TextColumn::make('conversions')
                    ->label(__('filament.conversions'))
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('production_time')
                    ->label(__('filament.production_time'))
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('revenue')
                    ->label(__('filament.revenue'))
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
            ])
            ->filters([
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
                                $data['date_from'] ?? null,
                                fn (Builder $builder, $date): Builder => $builder->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'] ?? null,
                                fn (Builder $builder, $date): Builder => $builder->whereDate('date', '<=', $date),
                            );
                    }),
                SelectFilter::make('employee_id')
                    ->label(__('filament.employee'))
                    ->options(fn (): array => ModelListService::make(
                        model: Employee::query(),
                        value_field: 'full_name',
                    ))
                    ->searchable(),
                SelectFilter::make('campaign_id')
                    ->label(__('filament.campaign'))
                    ->options(fn (): array => ModelListService::make(model: Campaign::query()))
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
