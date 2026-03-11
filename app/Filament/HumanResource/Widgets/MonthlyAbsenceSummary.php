<?php

namespace App\Filament\HumanResource\Widgets;

use App\Models\Employee;
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
                    ->sortable(),
            ])
            ->defaultSort('absences_count', 'desc')
            ->paginated(false);
    }

    protected function getTableQuery(): Builder
    {
        $query = Employee::query()
            ->withCount(['absences' => function ($query): void {
                $query->whereBetween('date', [
                    now()->startOfMonth()->format('Y-m-d'),
                    now()->endOfMonth()->format('Y-m-d'),
                ]);
            }])
            ->having('absences_count', '>', 0);

        if (isset($this->filters['site']) && ! empty($this->filters['site'])) {
            $query->whereHas('site', function ($q): void {
                $q->whereIn('id', $this->filters['site']);
            });
        }

        if (isset($this->filters['project']) && ! empty($this->filters['project'])) {
            $query->whereHas('project', function ($q): void {
                $q->whereIn('id', $this->filters['project']);
            });
        }

        if (isset($this->filters['supervisor']) && ! empty($this->filters['supervisor'])) {
            $query->whereHas('supervisor', function ($q): void {
                $q->whereIn('id', $this->filters['supervisor']);
            });
        }

        return $query->orderByDesc('absences_count');
    }
}
