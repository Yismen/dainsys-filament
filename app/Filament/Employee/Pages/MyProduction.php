<?php

namespace App\Filament\Employee\Pages;

use App\Models\Campaign;
use App\Models\Production;
use App\Models\Project;
use App\Services\ModelListService;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;

class MyProduction extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'My Productions';

    protected static ?string $title = 'My Productions';

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
            ->query(Production::query()->where('employee_id', Auth::user()->employee_id))
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->wrap()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                TextColumn::make('campaign.project.name')
                    ->label('Project')
                    ->wrap()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('campaign.name')
                    ->label('Campaign')
                    ->wrap()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('conversions')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->wrapHeader()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('total_time')
                    ->numeric(decimalPlaces: 2)
                    ->wrapHeader()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('production_time')
                    ->numeric(decimalPlaces: 2)
                    ->wrapHeader()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('talk_time')
                    ->numeric(decimalPlaces: 2)
                    ->wrapHeader()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('billable_time')
                    ->numeric(decimalPlaces: 2)
                    ->wrapHeader()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('sph_goal')
                    ->label('SPH Goal')
                    ->wrapHeader()
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize(
                        Summarizer::make()
                            ->label('Weighted Avg')
                            ->using(function (QueryBuilder $query): ?string {
                                // Calculate weighted average: (sum of conversions) / (sum of billable_time) compared to average goal
                                $totals = $query->selectRaw('
                                    SUM(conversions) as total_conversions,
                                    SUM(billable_time) as total_billable_time,
                                    AVG(sph_goal) as avg_goal
                                ')->first();

                                if (! $totals || $totals->total_billable_time == 0 || $totals->avg_goal == 0) {
                                    return null;
                                }

                                return number_format($totals->avg_goal, 2);
                            })
                    ),

                TextColumn::make('actual_sph')
                    ->label('% to Goal')
                    ->state(function (Production $record): ?string {
                        if ($record->billable_time == 0 || $record->sph_goal == 0) {
                            return null;
                        }

                        $actualSph = $record->conversions / $record->billable_time;
                        $percentage = ($actualSph / $record->sph_goal) * 100;

                        return number_format($percentage, 1).'%';
                    })
                    ->color(fn (Production $record): string => $record->billable_time > 0 && $record->sph_goal > 0 &&
                        (($record->conversions / $record->billable_time) / $record->sph_goal) >= 1
                            ? 'success'
                            : 'danger'
                    )
                    ->weight(FontWeight::Bold)
                    ->summarize(
                        Summarizer::make()
                            ->label('Overall')
                            ->using(function (QueryBuilder $query): ?string {
                                // Calculate overall performance: total conversions / total billable time vs average goal
                                $totals = $query->selectRaw('
                                    SUM(conversions) as total_conversions,
                                    SUM(billable_time) as total_billable_time,
                                    AVG(sph_goal) as avg_goal
                                ')->first();

                                if (! $totals || $totals->total_billable_time == 0 || $totals->avg_goal == 0) {
                                    return null;
                                }

                                $actualSph = $totals->total_conversions / $totals->total_billable_time;
                                $percentage = ($actualSph / $totals->avg_goal) * 100;

                                return number_format($percentage, 1).'%';
                            })
                    ),
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

                SelectFilter::make('project_id')
                    ->label('Project')
                    ->options(ModelListService::make(Project::query()))
                    ->searchable()
                    ->query(function (Builder $query, $value): Builder {
                        return $query->when($value, function (Builder $query, $value): Builder {
                            return $query->whereHas('campaign', function (Builder $query) use ($value): void {
                                $query->where('project_id', $value);
                            });
                        });
                    }),

                SelectFilter::make('campaign_id')
                    ->label('Campaign')
                    ->options(ModelListService::make(Campaign::query()))
                    ->searchable(),
            ])
            ->filtersFormColumns(2);
    }
}
