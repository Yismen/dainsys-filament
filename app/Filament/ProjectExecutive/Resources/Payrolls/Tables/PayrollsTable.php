<?php

namespace App\Filament\ProjectExecutive\Resources\Payrolls\Tables;

use App\Models\Employee;
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

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('payable_date', 'desc')
            ->columns([
                TextColumn::make('payable_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('gross_income')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('net_payroll')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('total_payroll')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
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
                                $data['payable_date_from'] ?? null,
                                fn (Builder $builder, $date): Builder => $builder->whereDate('payable_date', '>=', $date),
                            )
                            ->when(
                                $data['payable_date_until'] ?? null,
                                fn (Builder $builder, $date): Builder => $builder->whereDate('payable_date', '<=', $date),
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
            ])
            ->filtersFormColumns(2)
            ->filtersFormWidth(Width::Large)
            ->recordActions([
                ViewAction::make(),
            ])
            ->paginated([10, 25, 50, 100]);
    }
}
