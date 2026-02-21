<?php

namespace App\Filament\Employee\Pages;

use App\Models\Incentive;
use App\Models\Project;
use App\Services\ModelListService;
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

class MyIncentives extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'My Incentives';

    protected static ?string $title = 'My Incentives';

    protected static ?int $navigationSort = 4;

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
            ->query(Incentive::query()->where('employee_id', Auth::user()->employee_id))
            ->defaultSort('payable_date', 'desc')
            ->columns([
                TextColumn::make('payable_date')
                    ->date()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                TextColumn::make('project.name')
                    ->label('Project')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('total_production_hours')
                    ->label('Production Hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('total_sales')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('amount')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),
            ])
            ->filters([
                Filter::make('payable_date')
                    ->label('Payable Date')
                    ->columnSpanFull()
                    ->indicateUsing(function (array $state): ?string {
                        if ($state['payable_date_from'] && $state['payable_date_until']) {
                            return "Payable date from {$state['payable_date_from']} until {$state['payable_date_until']}";
                        }

                        if ($state['payable_date_from']) {
                            return "Payable date from {$state['payable_date_from']}";
                        }

                        if ($state['payable_date_until']) {
                            return "Payable date until {$state['payable_date_until']}";
                        }

                        return null;
                    })
                    ->schema([
                        DatePicker::make('payable_date_from')
                            ->label('Payable date from')
                            ->placeholder('Start date')
                            ->minDate(now()->subYear())
                            ->maxDate(now()),
                        DatePicker::make('payable_date_until')
                            ->label('Payable date until')
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

                SelectFilter::make('project_id')
                    ->label('Project')
                    ->options(ModelListService::make(Project::query()))
                    ->searchable(),
            ])
            ->filtersFormColumns(2);
    }
}
