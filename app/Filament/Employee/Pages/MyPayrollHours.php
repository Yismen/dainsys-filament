<?php

namespace App\Filament\Employee\Pages;

use App\Models\PayrollHour;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MyPayrollHours extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'My Payroll Hours';

    protected static ?string $title = 'My Payroll Hours';

    protected static ?int $navigationSort = 2;

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
                    ->label(__('filament.total_hours'))
                    ->numeric(decimalPlaces: 2)
                    ->formatStateUsing(fn (float $state): string => $state == 0 ? '-' : number_format($state, 2))
                    ->sortable()
                    ->summarize(Sum::make()->label(__('filament.total'))),

                TextColumn::make('regular_hours')
                    ->label(__('filament.regular_hours'))
                    ->numeric(decimalPlaces: 2)
                    ->formatStateUsing(fn (float $state): string => $state == 0 ? '-' : number_format($state, 2))
                    ->sortable()
                    ->summarize(Sum::make()->label(__('filament.total'))),

                TextColumn::make('overtime_hours')
                    ->label(__('filament.overtime_hours'))
                    ->numeric(decimalPlaces: 2)
                    ->formatStateUsing(fn (float $state): string => $state == 0 ? '-' : number_format($state, 2))
                    ->sortable()
                    ->summarize(Sum::make()->label(__('filament.total'))),

                TextColumn::make('holiday_hours')
                    ->label(__('filament.holiday_hours'))
                    ->numeric(decimalPlaces: 2)
                    ->formatStateUsing(fn (float $state): string => $state == 0 ? '-' : number_format($state, 2))
                    ->sortable()
                    ->summarize(Sum::make()->label(__('filament.total'))),

                TextColumn::make('seventh_day_hours')
                    ->label(__('filament.seventh_day_hours'))
                    ->numeric(decimalPlaces: 2)
                    ->formatStateUsing(fn (float $state): string => $state == 0 ? '-' : number_format($state, 2))
                    ->sortable()
                    ->summarize(Sum::make()->label(__('filament.total'))),

                TextColumn::make('week_ending_at')
                    ->label(__('filament.week_ending'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('payroll_ending_at')
                    ->label(__('filament.payroll_ending'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_sunday')
                    ->label(__('filament.is_sunday'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_holiday')
                    ->label(__('filament.is_holiday'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('week_ending_at')
                    ->label(__('filament.week_ending'))
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
                    ->label(__('filament.payroll_ending'))
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
