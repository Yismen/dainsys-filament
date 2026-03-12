<?php

namespace App\Filament\Employee\Pages;

use App\Enums\AbsenceStatuses;
use App\Enums\AbsenceTypes;
use App\Models\Absence;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MyAbsences extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $navigationLabel = 'My Absences';

    protected static ?string $title = 'My Absences';

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
            ->query(Absence::query()
                ->with(['employee'])
                ->where('employee_id', Auth::user()->employee_id))
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('type')
                    ->badge()
                    ->placeholder('Pending')
                    ->sortable(),

                TextColumn::make('comment')
                    ->limit(50)
                    ->placeholder('-'),

                TextColumn::make('creator.name')
                    ->label('Reported By')
                    ->searchable(),
            ])
            ->filters([
                Filter::make('current_month')
                    ->label('Current Month')
                    ->query(fn (Builder $query): Builder => $query->whereBetween('date', [
                        now()->startOfMonth()->format('Y-m-d'),
                        now()->endOfMonth()->format('Y-m-d'),
                    ]))
                    ->indicateUsing(function (array $data) {
                        return 'Current Month';
                    }),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options(AbsenceStatuses::toArray())
                    ->searchable(),

                SelectFilter::make('type')
                    ->label('Type')
                    ->options(AbsenceTypes::toArray())
                    ->searchable(),
            ])
            ->filtersFormColumns(2);
    }
}
