<?php

namespace App\Filament\OperationsDirector\Widgets;

use App\Filament\OperationsDirector\Widgets\Concerns\InteractsWithProjectAndClientFilters;
use App\Models\Employee;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class AbsencesByEmployeeTable extends TableWidget
{
    use InteractsWithProjectAndClientFilters;

    protected static ?string $heading = 'Absences by employee (last 30 days)';

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('full_name')
                    ->wrap()
                    ->label(__('filament.employee'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('project.name')
                    ->label(__('filament.project'))
                    ->sortable(),
                TextColumn::make('absences_count')
                    ->label(__('filament.absences'))
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 5 => Color::Red,
                        $state >= 3 => Color::Orange,
                        default => Color::Taupe,
                    }),
                TextColumn::make('absences.date')
                    ->label(__('filament.absence_dates'))
                    ->badge()
                    ->date('M d, Y')
                    ->wrap(),
            ])
            ->defaultSort('absences_count', 'desc')
            ->paginated(false);
    }

    protected function getTableQuery(): Builder
    {
        $projectIds = $this->getFilteredProjectIds();
        $from = now()->subDays(30)->startOfDay();

        return Employee::query()
            ->active()
            ->when(
                $projectIds !== [],
                fn ($query) => $query->whereIn('project_id', $projectIds),
            )
            ->when(
                ($projectIds === []) && $this->hasProjectOrClientFiltersApplied(),
                fn ($query) => $query->whereRaw('1 = 0'),
            )
            ->with('project:id,name')
            ->with(['absences' => function ($query) use ($from): void {
                $query->whereDate('date', '>=', $from)
                    ->orderByDesc('date');
            }])
            ->withCount(['absences' => function ($query) use ($from): void {
                $query->whereDate('date', '>=', $from);
            }])
            ->whereHas('absences', function ($query) use ($from): void {
                $query->whereDate('date', '>=', $from);
            })
            ->orderByDesc('absences_count');
    }
}
