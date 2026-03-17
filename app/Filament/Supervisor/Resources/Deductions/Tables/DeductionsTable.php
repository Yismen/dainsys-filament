<?php

namespace App\Filament\Supervisor\Resources\Deductions\Tables;

use App\Models\Employee;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DeductionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('payable_date', 'desc')
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                TextColumn::make('payable_date')
                    ->label('Payable Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize([
                        Sum::make(),
                    ]),
                TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->wrap(),
            ])
            ->filters([
                Filter::make('payable_date')
                    ->columnSpanFull()
                    ->schema([
                        DatePicker::make('payable_date_from')
                            ->label('Payable date from'),
                        DatePicker::make('payable_date_until')
                            ->label('Payable date until'),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['payable_date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('payable_date', '>=', $date),
                            )
                            ->when(
                                $data['payable_date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('payable_date', '<=', $date),
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
