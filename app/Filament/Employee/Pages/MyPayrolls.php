<?php

namespace App\Filament\Employee\Pages;

use App\Models\Payroll;
use BackedEnum;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

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
                Split::make([
                    Grid::make(columns: 2) // grid 1
                        ->schema([
                            TextColumn::make('payable_date')
                                ->date()
                                ->sortable()
                                ->weight(FontWeight::Bold)
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Payable Date',
                                    'state' => Carbon::parse($state)->format('M d, Y'),
                                ])),

                            TextColumn::make('total_payroll')
                                ->numeric(decimalPlaces: 2)
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Total Payroll',
                                    'state' => Number::currency($state),
                                ]))
                                ->summarize(Sum::make()->label('Total Payroll')),

                            TextColumn::make('net_payroll')
                                ->numeric(decimalPlaces: 2)
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Net Payroll',
                                    'state' => Number::currency($state),
                                ])),

                            TextColumn::make('additional_incentives_1')
                                ->numeric(decimalPlaces: 2)
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Additional Incentives 1',
                                    'state' => Number::currency($state),
                                ])),

                            TextColumn::make('medical_licence')
                                ->numeric(decimalPlaces: 2)
                                ->wrapHeader()
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Medical Licence',
                                    'state' => Number::currency($state),
                                ])),

                            TextColumn::make('gross_income')
                                ->numeric(decimalPlaces: 2)
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Gross Income',
                                    'state' => Number::currency($state),
                                ]))
                                ->summarize(Sum::make()->label('Total Gross Income')),
                        ]),
                    Grid::make(2) // grid 2
                        ->schema([

                            TextColumn::make('salary_rate')
                                ->numeric(decimalPlaces: 2)
                                ->wrapHeader()
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Salary Rate',
                                    'state' => Number::currency($state),
                                ])),

                            TextColumn::make('total_hours')
                                ->numeric(decimalPlaces: 2)
                                ->wrapHeader()
                                ->formatstateusing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Total Hours',
                                    'state' => $state,
                                ])),

                            TextColumn::make('salary_income')
                                ->numeric(decimalPlaces: 2)
                                ->wrapHeader()
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Salary Income',
                                    'state' => Number::currency($state),
                                ])),

                            TextColumn::make('nightly_incomes')
                                ->numeric(decimalPlaces: 2)
                                ->wrapHeader()
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Nightly Incomes',
                                    'state' => Number::currency($state),
                                ])),

                            TextColumn::make('overtime_incomes')
                                ->numeric(decimalPlaces: 2)
                                ->wrapHeader()
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Overtime Incomes',
                                    'state' => Number::currency($state),
                                ])),

                            TextColumn::make('holiday_incomes')
                                ->numeric(decimalPlaces: 2)
                                ->wrapHeader()
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Holiday Incomes',
                                    'state' => Number::currency($state),
                                ])),

                            // TextColumn::make('additional_incentives_2')
                            //     ->numeric(decimalPlaces: 2)
                            //     ->wrapHeader()
                            //     ->sortable()
                            //     ->summarize(Sum::make()->label('Total')),
                        ]),
                    Grid::make(2)
                        ->schema([

                            TextColumn::make('total_deductions')
                                ->numeric(decimalPlaces: 2)
                                ->wrapHeader()
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Total Deductions',
                                    'state' => Number::currency($state),
                                ]))
                                ->summarize(Sum::make()->label('Total Deductions')),

                            TextColumn::make('deduction_ars')
                                ->numeric(decimalPlaces: 2)
                                ->wrapHeader()
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Deduction ARS',
                                    'state' => Number::currency($state),
                                ])),

                            TextColumn::make('deduction_afp')
                                ->numeric(decimalPlaces: 2)
                                ->wrapHeader()
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Deduction AFP',
                                    'state' => Number::currency($state),
                                ])),

                            TextColumn::make('deductions_other')
                                ->label('Deductions Other')
                                ->numeric(decimalPlaces: 2)
                                ->wrapHeader()
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Deductions Other',
                                    'state' => Number::currency($state),
                                ])),

                        ]),
                ]),
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
