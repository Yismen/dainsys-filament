<?php

namespace App\Filament\ProjectExecutive\Widgets;

use App\Models\Employee;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AbsencesByEmployeeTable extends TableWidget
{
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
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('project.name')
                    ->label('Project')
                    ->sortable(),
                TextColumn::make('absences_count')
                    ->label('Absences')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 5 => Color::Red,
                        $state >= 3 => Color::Orange,
                        default => Color::Taupe,
                    }),
                TextColumn::make('absences.date')
                    ->label('Absence dates')
                    ->badge()
                    ->date('M d, Y')
                    ->wrap(),
            ])
            ->defaultSort('absences_count', 'desc')
            ->paginated(false);
    }

    protected function getTableQuery(): Builder
    {
        $managerId = Auth::id();

        if (! $managerId) {
            return Employee::query()->whereRaw('1 = 0');
        }

        $from = now()->subDays(30)->startOfDay();

        return Employee::query()
            ->active()
            ->whereHas('project', function (Builder $query) use ($managerId): void {
                $query->where('manager_id', $managerId);
            })
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
