<?php

namespace App\Filament\Employee\Pages;

use App\Models\NightlyHour;
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

class MyNightlyHours extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-moon';

    protected static ?string $navigationLabel = 'My Nightly Hours';

    protected static ?string $title = 'My Nightly Hours';

    protected static ?int $navigationSort = 3;

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
            ->query(NightlyHour::query()
                ->where('employee_id', Auth::user()->employee_id))
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                TextColumn::make('total_hours')
                    ->label('Nightly Hours')
                    ->numeric(decimalPlaces: 2)
                    ->formatStateUsing(fn (float $state): string => $state == 0 ? '-' : number_format($state, 2))
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('created_at')
                    ->label('Recorded At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('date')
                    ->columnSpanFull()
                    ->indicateUsing(function (array $data) {
                        if ($data['date_from'] && $data['date_until']) {
                            return "From {$data['date_from']} to {$data['date_until']}";
                        }

                        if ($data['date_from']) {
                            return "From {$data['date_from']}";
                        }

                        if ($data['date_until']) {
                            return "Until {$data['date_until']}";
                        }

                        return null;
                    })
                    ->schema([
                        DatePicker::make('date_from')
                            ->label('Date from')
                            ->placeholder('Start date')
                            ->minDate(now()->subYear())
                            ->maxDate(now()),
                        DatePicker::make('date_until')
                            ->label('Date until')
                            ->placeholder('End date')
                            ->minDate(now()->subYear())
                            ->maxDate(now()),
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
            ]);
    }
}
