<?php

namespace App\Filament\Employee\Pages;

use App\Models\PayrollHour;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MyPayrollHours extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'My Hours';

    protected static ?string $title = 'My Hours';

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
            ->query(PayrollHour::query()
                ->where('employee_id', Auth::user()->employee_id))
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                TextColumn::make('total_hours')
                    ->label('Total Hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('nightly_hours')
                    ->label('Nightly Hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('regular_hours')
                    ->label('Regular Hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('overtime_hours')
                    ->label('Overtime Hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('holiday_hours')
                    ->label('Holiday Hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('seventh_day_hours')
                    ->label('7th Day Hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('week_ending_at')
                    ->label('Week Ending')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('payroll_ending_at')
                    ->label('Payroll Ending')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('date')
                    ->schema([
                        DatePicker::make('date_from')
                            ->label('Date from')
                            ->placeholder('Start date'),
                        DatePicker::make('date_until')
                            ->label('Date until')
                            ->placeholder('End date'),
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

                SelectFilter::make('week_ending_at')
                    ->label('Week Ending')
                    ->options(fn (): array => Cache::remember(
                        'payroll_hours_week_endings_'.Auth::user()->employee_id,
                        now()->addHour(),
                        fn () => PayrollHour::query()
                            ->where('employee_id', Auth::user()->employee_id)
                            ->whereNotNull('week_ending_at')
                            ->distinct()
                            ->orderBy('week_ending_at', 'desc')
                            ->pluck('week_ending_at')
                            ->mapWithKeys(fn ($date) => [$date->format('Y-m-d') => $date->format('M d, Y')])
                            ->toArray()
                    ))
                    ->placeholder('All weeks'),

                SelectFilter::make('payroll_ending_at')
                    ->label('Payroll Ending')
                    ->options(fn (): array => Cache::remember(
                        'payroll_hours_payroll_endings_'.Auth::user()->employee_id,
                        now()->addHour(),
                        fn () => PayrollHour::query()
                            ->where('employee_id', Auth::user()->employee_id)
                            ->whereNotNull('payroll_ending_at')
                            ->distinct()
                            ->orderBy('payroll_ending_at', 'desc')
                            ->pluck('payroll_ending_at')
                            ->mapWithKeys(fn ($date) => [$date->format('Y-m-d') => $date->format('M d, Y')])
                            ->toArray()
                    ))
                    ->placeholder('All payroll periods'),
            ]);
    }
}
