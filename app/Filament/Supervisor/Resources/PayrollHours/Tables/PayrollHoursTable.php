<?php

namespace App\Filament\Supervisor\Resources\PayrollHours\Tables;

use App\Models\Employee;
use App\Services\ModelListService;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PayrollHoursTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('regular_hours')
                    ->label('Regular Hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('overtime_hours')
                    ->label('Overtime Hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('holiday_hours')
                    ->label('Holiday Hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('seventh_day_hours')
                    ->label('Seventh Day Hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('total_hours')
                    ->label('Total Hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('week_ending_at')
                    ->label('Week Ending')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('date')
                    ->columnSpanFull()
                    ->schema([
                        \Filament\Forms\Components\DatePicker::make('date_from')
                            ->label('Date from'),
                        \Filament\Forms\Components\DatePicker::make('date_until')
                            ->label('Date until'),
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
                SelectFilter::make('employee_id')
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
            ->paginated([10, 25, 50, 100]);
    }
}
