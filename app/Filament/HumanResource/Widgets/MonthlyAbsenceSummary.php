<?php

namespace App\Filament\HumanResource\Widgets;

use App\Models\Employee;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class MonthlyAbsenceSummary extends TableWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Monthly Absence Summary';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('full_name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('absences_count')
                    ->label('Absences')
                    ->counts('absences')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 5 => Color::Red,
                        $state >= 3 => Color::Orange,
                        default => Color::Taupe,
                    }),
            ])
            ->defaultSort('absences_count', 'desc')
            ->paginated(false);
    }

    protected function getTableQuery(): Builder
    {
        $query = Employee::query()
            ->withCount(['absences' => function ($query): void {
                $query->whereBetween('date', [
                    now()->startOfMonth(),
                    now()->endOfMonth(),
                ]);
            }])
            ->having('absences_count', '>', 0);

        $siteFilter = $this->filters['site'] ?? null;
        if ($siteFilter) {
            $query->whereHas('site', function ($q) use ($siteFilter): void {
                $q->whereIn('id', $siteFilter);
            });
        }

        $projectFilter = $this->filters['project'] ?? null;
        if ($projectFilter) {
            $query->whereHas('project', function ($q) use ($projectFilter): void {
                $q->whereIn('id', $projectFilter);
            });
        }

        $supervisorFilter = $this->filters['supervisor'] ?? null;
        if ($supervisorFilter) {
            $query->whereHas('supervisor', function ($q) use ($supervisorFilter): void {
                $q->whereIn('id', $supervisorFilter);
            });
        }

        return $query->orderByDesc('absences_count');
    }
}
