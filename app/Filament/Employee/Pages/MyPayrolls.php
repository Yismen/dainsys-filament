<?php

namespace App\Filament\Employee\Pages;

use App\Models\Payroll;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MyPayrolls extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'My Payrolls';

    protected static ?string $title = 'My Payrolls';

    protected static ?int $navigationSort = 5;

    public function mount(): void
    {
        $user = Auth::user();

        if (! $user->employee_id) {
            abort(403, 'No employee record found.');
        }
    }

    public function getView(): string
    {
        return 'filament.pages.table-page';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Payroll::query()->where('employee_id', Auth::user()->employee_id))
            ->defaultSort('payable_date', 'desc')
            ->columns([
                TextColumn::make('payable_date')
                    ->date()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                TextColumn::make('gross_income')
                    ->numeric(decimalPlaces: 2)
                    ->wrapHeader()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('net_payroll')
                    ->numeric(decimalPlaces: 2)
                    ->wrapHeader()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('taxable_payroll')
                    ->numeric(decimalPlaces: 2)
                    ->wrapHeader()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('hourly_rate')
                    ->numeric(decimalPlaces: 2)
                    ->wrapHeader()
                    ->sortable(),

                TextColumn::make('regular_hours')
                    ->numeric(decimalPlaces: 2)
                    ->wrapHeader()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('overtime_hours')
                    ->numeric(decimalPlaces: 2)
                    ->wrapHeader()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('holiday_hours')
                    ->numeric(decimalPlaces: 2)
                    ->wrapHeader()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('night_shift_hours')
                    ->numeric(decimalPlaces: 2)
                    ->wrapHeader()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('additional_incentives_1')
                    ->numeric(decimalPlaces: 2)
                    ->wrapHeader()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('additional_incentives_2')
                    ->numeric(decimalPlaces: 2)
                    ->wrapHeader()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('deduction_afp')
                    ->numeric(decimalPlaces: 2)
                    ->wrapHeader()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('deduction_ars')
                    ->numeric(decimalPlaces: 2)
                    ->wrapHeader()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('other_deductions')
                    ->numeric(decimalPlaces: 2)
                    ->wrapHeader()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),
            ])
            ->filters([
                Filter::make('payable_date')
                    ->schema([
                        DatePicker::make('payable_date_from')
                            ->label('Payable date from')
                            ->placeholder('Start date'),
                        DatePicker::make('payable_date_until')
                            ->label('Payable date until')
                            ->placeholder('End date'),
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
            ])
            ->filtersFormColumns(2);
    }
}
