<?php

namespace App\Filament\Workforce\Widgets;

use App\Enums\DowntimeStatuses;
use App\Models\Downtime;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentDowntimesTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Recent downtimes';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->query($this->getQuery())
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('campaign.name')
                    ->label('Campaign')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('downtimeReason.name')
                    ->label('Reason')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('total_time')
                    ->label('Minutes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (DowntimeStatuses|string|null $state): ?string => $state instanceof DowntimeStatuses ? $state->getColor() : null)
                    ->sortable(),
                TextColumn::make('aprover.name')
                    ->label('Approver')
                    ->wrap()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Requested at')
                    ->dateTime()
                    ->since()
                    ->sortable(),
            ]);
    }

    protected function getQuery(): Builder
    {
        return Downtime::query()
            ->with([
                'employee',
                'campaign',
                'downtimeReason',
                'aprover',
            ])
            ->whereDate('date', '>=', now()->subDays(14))
            ->latest('date');
    }
}
