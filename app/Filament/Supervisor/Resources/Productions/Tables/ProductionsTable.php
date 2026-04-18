<?php

namespace App\Filament\Supervisor\Resources\Productions\Tables;

use App\Models\Campaign;
use App\Models\Employee;
use App\Models\Production;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ProductionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label(__('filament.employee'))
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                TextColumn::make('date')
                    ->label(__('filament.date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('campaign.name')
                    ->label(__('filament.campaign'))
                    ->sortable()
                    ->searchable()
                    ->limit(25)
                    ->tooltip(fn (Production $record) => $record->campaign?->name)
                    ->wrap(),
                TextColumn::make('total_time')
                    ->wrapHeader()
                    ->label(__('filament.total_time'))
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('talk_time')
                    ->wrapHeader()
                    ->label(__('filament.talk_time'))
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('billable_time')
                    ->wrapHeader()
                    ->label(__('filament.billable_time'))
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('conversions')
                    ->label(__('filament.conversions'))
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('sph_goal')
                    ->wrapHeader()
                    ->label(__('filament.sph_goal'))
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make(name: 'sph')
                    ->wrapHeader()
                    ->label(__('filament.sph'))
                    ->numeric(decimalPlaces: 2)
                    ->state(fn (Production $record) => $record->billable_time > 0 ? round($record->conversions / $record->billable_time, 2) : 0)
                    ->sortable(),
            ])
            ->filters([
                Filter::make('date')
                    ->columnSpanFull()
                    ->schema([
                        DatePicker::make('date_from')
                            ->label(__('filament.date_from')),
                        DatePicker::make('date_until')
                            ->label(__('filament.date_until')),
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
                SelectFilter::make('employee_id')
                    ->label(__('filament.employee'))
                    ->options(ModelListService::make(
                        model: Employee::query()
                            ->active()
                            ->whereHas('supervisor', function (Builder $query): void {
                                $query->where('id', Auth::user()?->supervisor?->id);
                            }),
                        value_field: 'full_name',
                    ))
                    ->searchable(),
                SelectFilter::make('campaign_id')
                    ->label(__('filament.campaign'))
                    ->options(ModelListService::make(Campaign::query()))
                    ->searchable(),
            ])
            ->filtersFormColumns(2)
            ->filtersFormWidth(Width::Large)
            ->paginated([10, 25, 50, 100]);
    }
}
