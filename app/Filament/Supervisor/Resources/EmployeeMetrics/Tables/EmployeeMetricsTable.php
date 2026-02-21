<?php

namespace App\Filament\Supervisor\Resources\EmployeeMetrics\Tables;

use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class EmployeeMetricsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('full_name')
            ->columns([
                TextColumn::make('full_name')
                    ->label('Employee')
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                TextColumn::make('total_production_time')
                    ->wrapHeader()
                    ->label('Total Production Time')
                    ->state(function ($record) {
                        return $record->productions
                            ->sum('production_time');
                    })
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('total_conversions')
                    ->label('Total Conversions')
                    ->wrapHeader()
                    ->state(function ($record) {
                        return $record->productions
                            ->sum('conversions');
                    })
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('conversions_goal')
                    ->label('Conversions Goal')
                    ->wrapHeader()
                    ->state(function ($record) {
                        return $record->productions
                            ->sum('conversions_goal');
                    })
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('sph')
                    ->label('SPH')
                    ->wrapHeader()
                    ->state(function ($record) {
                        $conversions = $record->productions
                            ->sum('conversions');

                        $productionHours = $record->productions
                            ->sum('production_time');

                        return $productionHours > 0 ? $conversions / ($productionHours) : 0;
                    })
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('sph_percentage')
                    ->label('SPH % to Goal')
                    ->wrapHeader()
                    ->state(function ($record) {
                        $conversions = $record->productions
                            ->sum('conversions');

                        $conversionsGoal = $record->productions
                            ->sum('conversions_goal');

                        return $conversionsGoal > 0 ? ($conversions / $conversionsGoal) * 100 : 0;
                    })
                    ->formatStateUsing(fn ($state) => round($state, 1).'%')
                    ->badge()
                    ->color(function ($state) {
                        if ($state >= 100) {
                            return 'success';
                        } elseif ($state >= 80) {
                            return 'warning';
                        } else {
                            return 'danger';
                        }
                    }),
                TextColumn::make('efficiency_rate')
                    ->label('Efficiency Rate %')
                    ->wrapHeader()
                    ->state(function ($record) {
                        $totalHours = $record->productions
                            ->sum('total_time');

                        $billableHours = $record->productions
                            ->sum('billable_time');

                        return $totalHours > 0 ? ($billableHours / $totalHours) * 100 : 0;
                    })
                    ->formatStateUsing(fn ($state) => round($state, 1).'%')
                    ->badge()
                    ->color(function ($state) {
                        if ($state >= 100) {
                            return 'success';
                        } elseif ($state >= 90) {
                            return 'warning';
                        } else {
                            return 'danger';
                        }
                    }),
            ])
            ->filters([
                Filter::make('date')
                    ->columnSpanFull()
                    ->columns(2)
                    ->indicateUsing(function ($data) {
                        if (isset($data['date_from']) && isset($data['date_until'])) {
                            return 'From '.$data['date_from'].' until '.$data['date_until'];
                        }

                        if (isset($data['date_from'])) {
                            return 'From '.$data['date_from'];
                        }

                        if (isset($data['date_until'])) {
                            return 'Until '.$data['date_until'];
                        }

                        return null;
                    })
                    ->schema([
                        DatePicker::make('date_from')
                            ->label('From')
                            ->default(now()->subMonth())
                            ->minDate(now()->subYear())
                            ->maxDate(now()),
                        DatePicker::make('date_until')
                            ->label('Until')
                            ->default(now())
                            ->minDate(now()->subYear())
                            ->maxDate(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['date_from'],
                            fn($q, $date) => $q->whereHas('productions', fn($q) => $q->whereDate('date', '>=', $date))
                        )
                        ->when(
                            $data['date_until'],
                            fn($q, $date) => $q->whereHas('productions', fn($q) => $q->whereDate('date', '<=', $date))
                        );
                    }),
            ])
            ->filtersFormColumns(2)
            ->filtersFormWidth(Width::Large);
    }
}
