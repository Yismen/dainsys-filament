<?php

namespace App\Filament\Workforce\Resources\PayrollHours;

use App\Actions\Filament\UpdatePayrollHoursAction;
use App\Filament\Workforce\Resources\PayrollHours\Pages\ManagePayrollHours;
use App\Models\Employee;
use App\Models\PayrollHour;
use App\Services\ModelListService;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Cache;
use UnitEnum;

class PayrollHourResource extends Resource
{
    protected static ?string $model = PayrollHour::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $recordTitleAttribute = 'full_name';

    protected static ?int $navigationSort = 8;

    protected static string|UnitEnum|null $navigationGroup = 'Management';

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('full_name')
            ->defaultSort('date', 'desc')
            ->headerActions([
                UpdatePayrollHoursAction::make(),
            ])
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('date')
                    ->date()
                    ->wrap()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('total_hours')
                    ->numeric()
                    ->sortable()
                    ->wrapHeader()
                    ->summarize(Sum::make()),
                TextColumn::make('regular_hours')
                    ->numeric()
                    ->sortable()
                    ->wrapHeader()
                    ->summarize(Sum::make()),
                TextColumn::make('overtime_hours')
                    ->numeric()
                    ->sortable()
                    ->wrapHeader()
                    ->summarize(Sum::make()),
                TextColumn::make('holiday_hours')
                    ->numeric()
                    ->sortable()
                    ->wrapHeader()
                    ->summarize(Sum::make()),
                TextColumn::make('seventh_day_hours')
                    ->numeric()
                    ->sortable()
                    ->wrapHeader()
                    ->summarize(Sum::make()),
                TextColumn::make('week_ending_at')
                    ->date()
                    ->sortable()
                    ->wrap()
                    ->wrapHeader(),
                TextColumn::make('payroll_ending_at')
                    ->date()
                    ->sortable()
                    ->wrap()
                    ->wrapHeader(),
                IconColumn::make('is_sunday')
                    ->boolean()
                    ->wrapHeader(),
                IconColumn::make('is_holiday')
                    ->boolean()
                    ->wrapHeader(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filtersFormColumns(2)
            ->filtersFormWidth(Width::ThreeExtraLarge)
            ->filters([
                Filter::make('date')
                    ->label('Date Range')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('from')
                            ->label('Date From'),
                        DatePicker::make('to')
                            ->label('Date To'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn (Builder $query, $date) => $query->where('date', '>=', $date))
                            ->when($data['to'], fn (Builder $query, $date) => $query->where('date', '<=', $date));
                    }),
                SelectFilter::make('week_ending_at')
                    ->options(function () {
                        return Cache::rememberForever('payroll_week_ending_dates', function () {
                            return PayrollHour::query()
                                ->select('week_ending_at')
                                ->distinct()
                                ->orderBy('week_ending_at', 'desc')
                                ->pluck('week_ending_at')
                                ->mapWithKeys(fn ($date) => [
                                    $date->toDateString() => $date->toFormattedDateString(),
                                ]);
                        });
                    }),

                SelectFilter::make('payroll_ending_at')
                    ->options(function () {
                        return Cache::rememberForever('payroll_ending_dates', function () {
                            return PayrollHour::query()
                                ->select('payroll_ending_at')
                                ->distinct()
                                ->orderBy('payroll_ending_at', 'desc')
                                ->pluck('payroll_ending_at')
                                ->mapWithKeys(fn ($date) => [
                                    $date->toDateString() => $date->toFormattedDateString(),
                                ]);
                        });
                    }),
                SelectFilter::make('employee_id')
                    ->options(ModelListService::make(
                        model: Employee::query(),
                        value_field: 'full_name',
                    ))
                    ->searchable(),
            ])
            ->recordActions([
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePayrollHours::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
