<?php

namespace App\Filament\Employee\Pages;

use App\Models\Campaign;
use App\Models\Downtime;
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

class MyDowntimes extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-pause-circle';

    protected static ?string $navigationLabel = 'My Downtimes';

    protected static ?string $title = 'My Downtimes';

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
            ->query(Downtime::query()
                ->with(['campaign.project', 'downtimeReason'])
                ->where('employee_id', Auth::user()->employee_id))
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                TextColumn::make('campaign.project.name')
                    ->label('Project')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('campaign.name')
                    ->label('Campaign')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('downtime_reason.name')
                    ->label('Reason')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('total_time')
                    ->label('Total Time')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
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
