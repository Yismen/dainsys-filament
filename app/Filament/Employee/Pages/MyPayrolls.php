<?php

namespace App\Filament\Employee\Pages;

use App\Models\Payroll;
use BackedEnum;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Panel;
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
            ->contentGrid([
                'lg' => 2,
                'md' => 2,
            ])
            ->columns([
                Grid::make([
                    'default' => 2,
                    'xl' => 4,
                ])
                    ->schema([
                        TextColumn::make('payable_date')
                            ->weight(FontWeight::Bold)
                            ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                'label' => 'Payable Date',
                                'state' => Carbon::parse($state)->format('M d, Y'),
                            ])),

                        TextColumn::make('gross_income')
                            ->weight(FontWeight::Bold)
                            ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                'label' => 'Gross Income',
                                'state' => Number::currency($state),
                            ])),

                        TextColumn::make('total_deductions')
                            ->weight(FontWeight::Bold)
                            ->extraAttributes([
                                'class' => 'text-danger-600',
                            ])
                            ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                'label' => 'Total Deductions',
                                'state' => Number::currency($state),
                                'stateColor' => 'text-danger-600 dark:text-danger-400',
                            ])),

                        TextColumn::make('total_payroll')
                            ->weight(FontWeight::Bold)
                            ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                'label' => 'Total Payroll',
                                'state' => Number::currency($state),
                                'stateColor' => 'text-blue-600 dark:text-blue-400',
                            ])),
                    ]),
                Panel::make([
                    Grid::make([
                        'default' => 2,
                        'xl' => 4,
                    ])
                        ->schema([
                            TextColumn::make('salary_rate')
                                ->label(__('filament.salary_rate'))
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Salary Rate',
                                    'state' => Number::currency($state),
                                ])),

                            TextColumn::make('total_hours')
                                ->label(__('filament.total_hours'))
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Total Hours',
                                    'state' => Number::format($state, 2),
                                ])),
                            TextColumn::make('medical_licence')
                                ->label(__('filament.medical_licence'))
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Medical Licence',
                                    'state' => Number::currency($state),
                                ])),
                            TextColumn::make('salary_income')
                                ->label(__('filament.salary_income'))
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Salary Income',
                                    'state' => Number::currency($state),
                                ])),
                        ]),
                    Grid::make([
                        'default' => 2,
                        'xl' => 4,
                    ])
                        ->extraAttributes([
                            'class' => 'mt-4',
                        ])
                        ->schema([

                            TextColumn::make('nightly_incomes')
                                ->label(__('filament.nightly_incomes'))
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Nightly Incomes',
                                    'state' => Number::currency($state),
                                ])),
                            TextColumn::make('overtime_incomes')
                                ->label(__('filament.overtime_incomes'))
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Overtime Incomes',
                                    'state' => Number::currency($state),
                                ])),
                            TextColumn::make('holiday_incomes')
                                ->label(__('filament.holiday_incomes'))
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Holiday Incomes',
                                    'state' => Number::currency($state),
                                ])),
                            TextColumn::make('additional_incentives_2')
                                ->label(__('filament.additional_incentives_2'))
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Additional Payment',
                                    'state' => Number::currency($state),
                                ])),
                        ]),
                    // Grid::make([
                    //     'default' => 3,
                    // ])
                    // ->extraAttributes([
                    //     'class' => 'mt-4',
                    // ])
                    // ->schema([
                    //     TextColumn::make('net_payroll')
                    //         ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                    //             'label' => 'Payment 1',
                    //             'state' => Number::currency($state),
                    //         ])),
                    //     TextColumn::make('additional_incentives_1')
                    //         ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                    //             'label' => 'Payment 2',
                    //             'state' => Number::currency($state),
                    //         ])),

                    // ]),

                    Grid::make([
                        'default' => 3,
                    ])
                        ->extraAttributes([
                            'class' => 'mt-4',
                        ])
                        ->schema([
                            TextColumn::make('deduction_ars')
                                ->label(__('filament.deduction_ars'))
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Deduction ARS',
                                    'state' => Number::currency($state),
                                    'stateColor' => 'text-danger-600 dark:text-danger-400',
                                ])),
                            TextColumn::make('deduction_afp')
                                ->label(__('filament.deduction_afp'))
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Deduction AFP',
                                    'state' => Number::currency($state),
                                    'stateColor' => 'text-danger-600 dark:text-danger-400',
                                ])),
                            TextColumn::make('deductions_other')
                                ->label(__('filament.deductions_other'))
                                ->formatStateUsing(fn ($state) => view('filament.partials.tables.stacked-field', [
                                    'label' => 'Deductions Other',
                                    'state' => Number::currency($state),
                                    'stateColor' => 'text-danger-600 dark:text-danger-400',
                                ])),
                        ]),
                ])
                    ->collapsible()
                    ->collapsed(false),
            ])
            ->filters([
                Filter::make('payable_date')
                    ->columnSpanFull()
                    ->indicateUsing(function ($data) {
                        $from = $data['payable_date_from'] ? Carbon::parse($data['payable_date_from'])->format('M d, Y') : null;
                        $until = $data['payable_date_until'] ? Carbon::parse($data['payable_date_until'])->format('M d, Y') : null;

                        if ($from && $until) {
                            return "Payable Date: From {$from} until {$until}";
                        }

                        if ($from) {
                            return "Payable Date: From {$from}";
                        }

                        if ($until) {
                            return "Payable Date: Until {$until}";
                        }

                        return 'Payable Date';
                    })
                    ->schema([
                        DatePicker::make('payable_date_from')
                            ->label(__('filament.payable_date_from'))
                            ->placeholder('Start date')
                            ->minDate(now()->subYear())
                            ->maxDate(now()),
                        DatePicker::make('payable_date_until')
                            ->label(__('filament.payable_date_until'))
                            ->placeholder('End date')
                            ->minDate(now()->subYear())
                            ->maxDate(now()),
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
