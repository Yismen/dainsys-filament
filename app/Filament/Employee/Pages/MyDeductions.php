<?php

namespace App\Filament\Employee\Pages;

use App\Models\Deduction;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MyDeductions extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-minus-circle';

    protected static ?string $navigationLabel = 'My Deductions';

    protected static ?string $title = 'My Deductions';

    protected static ?int $navigationSort = 6;

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
            ->query(Deduction::query()->where('employee_id', Auth::user()->employee_id))
            ->defaultSort('payable_date', 'desc')
            ->columns([
                TextColumn::make('payable_date')
                    ->date()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                TextColumn::make('amount')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                TextColumn::make('description')
                    ->searchable()
                    ->wrap(),
            ])
            ->filters([
                SelectFilter::make('payable_date')
                    ->label('Payable Date')
                    ->columnSpanFull()
                    ->options(fn () => Deduction::query()
                        ->where('employee_id', Auth::user()->employee_id)
                        ->orderBy('payable_date', 'desc')
                        ->distinct()
                        ->pluck('payable_date', 'payable_date')
                        ->toArray())
                    ->placeholder('All Dates'),
            ])
            ->filtersFormColumns(2);
    }
}
